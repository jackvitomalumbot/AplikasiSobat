<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * DocumentWatermarkService
 *
 * Converts uploaded materi files (PDF / Word / PPT) to a watermarked PDF
 * before serving to mahasiswa. The watermark is the Sobat Medis logo,
 * centered on every page at 50% opacity.
 */
class DocumentWatermarkService
{
    /** Relative public path to the logo */
    protected string $logoPath;

    /** Watermark size as fraction of the page width (0.0 – 1.0) */
    protected float $logoScale = 0.42;

    /** Opacity: 0 (transparent) – 1 (opaque) */
    protected float $opacity = 0.50;

    public function __construct()
    {
        $this->logoPath = public_path('images/logo.png');
    }

    /* ────────────────────────────────────────────────────
     |  PUBLIC ENTRY POINT
     ─────────────────────────────────────────────────── */

    /**
     * Returns the absolute path to a watermarked PDF for the given file.
     * Results are cached; the cached file is regenerated if the source changes.
     *
     * @param string $filePath   Path relative to public_path() (e.g. "uploads/materi/foo.pdf")
     * @param string $fileName   Original file name (for cache key)
     * @return string            Absolute path to the watermarked PDF
     */
    public function processForDownload(string $filePath, string $fileName): string
    {
        $absoluteSource = public_path($filePath);

        if (!file_exists($absoluteSource)) {
            throw new \RuntimeException("Source file not found: {$absoluteSource}");
        }

        $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $cacheKey = 'watermarked/' . md5($filePath . filemtime($absoluteSource)) . '.pdf';
        $cachePath = storage_path('app/' . $cacheKey);

        // Return cached version if it exists
        if (file_exists($cachePath)) {
            return $cachePath;
        }

        $this->ensureDirectory(dirname($cachePath));

        // Step 1: get a plain PDF (convert if needed)
        $plainPdf = $this->ensurePdf($absoluteSource, $ext);

        // Step 2: overlay watermark on every page
        $this->overlayWatermark($plainPdf, $cachePath);

        // Clean up temp conversion file
        if ($plainPdf !== $absoluteSource && file_exists($plainPdf)) {
            @unlink($plainPdf);
        }

        return $cachePath;
    }

    /* ────────────────────────────────────────────────────
     |  STEP 1 — ENSURE WE HAVE A PLAIN PDF
     ─────────────────────────────────────────────────── */

    /**
     * If the file is already a PDF, return its path.
     * Otherwise, convert via LibreOffice → returns path to temp PDF.
     */
    protected function ensurePdf(string $absPath, string $ext): string
    {
        if ($ext === 'pdf') {
            return $absPath;
        }

        // Try LibreOffice (soffice)
        if ($this->isLibreOfficeAvailable()) {
            return $this->convertWithLibreOffice($absPath);
        }

        // Fallback: generate a simple PDF wrapper page via DomPDF
        return $this->convertWithDompdfFallback($absPath, $ext);
    }

    protected function isLibreOfficeAvailable(): bool
    {
        $soffice = $this->getSofficePath();
        exec($soffice . ' --version 2>/dev/null', $output, $code);
        return $code === 0;
    }

    protected function getSofficePath(): string
    {
        // Common install paths
        $paths = [
            'soffice',
            '/usr/bin/soffice',
            '/usr/lib/libreoffice/program/soffice',
            'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
        ];

        foreach ($paths as $p) {
            exec(escapeshellarg($p) . ' --version 2>/dev/null', $out, $code);
            if ($code === 0) return $p;
        }

        return 'soffice';
    }

    /**
     * Convert using LibreOffice headless.
     * Returns path to the resulting .pdf file.
     */
    protected function convertWithLibreOffice(string $absPath): string
    {
        $tmpDir  = sys_get_temp_dir() . '/sobatmedis_' . uniqid();
        @mkdir($tmpDir, 0755, true);

        $soffice = $this->getSofficePath();
        $cmd     = escapeshellarg($soffice)
            . ' --headless --convert-to pdf'
            . ' --outdir ' . escapeshellarg($tmpDir)
            . ' ' . escapeshellarg($absPath)
            . ' 2>/dev/null';

        exec($cmd, $output, $exitCode);

        $baseName  = pathinfo($absPath, PATHINFO_FILENAME);
        $converted = $tmpDir . DIRECTORY_SEPARATOR . $baseName . '.pdf';

        if ($exitCode !== 0 || !file_exists($converted)) {
            Log::warning("DocumentWatermarkService: LibreOffice conversion failed for {$absPath}");
            throw new \RuntimeException('Konversi file gagal. Pastikan LibreOffice terinstall di server.');
        }

        return $converted;
    }

    /**
     * Fallback: embed a notice page saying the file could not be converted.
     * This is used ONLY when LibreOffice is unavailable.
     */
    protected function convertWithDompdfFallback(string $absPath, string $ext): string
    {
        $tmpPdf  = sys_get_temp_dir() . '/sobatmedis_fallback_' . uniqid() . '.pdf';
        $extUp   = strtoupper($ext);
        $name    = basename($absPath);

        $html = <<<HTML
        <html><body style="font-family:Arial,sans-serif;text-align:center;padding:80px 60px;">
        <h2 style="color:#1c1c1e;">{$name}</h2>
        <p style="color:#555;font-size:16px;margin-top:20px;">
            File jenis <strong>{$extUp}</strong> tidak dapat dirender secara langsung.<br>
            Silakan hubungi pengajar untuk versi PDF.
        </p>
        </body></html>
        HTML;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
        file_put_contents($tmpPdf, $pdf->output());

        return $tmpPdf;
    }

    /* ────────────────────────────────────────────────────
     |  STEP 2 — OVERLAY WATERMARK ON EVERY PAGE
     ─────────────────────────────────────────────────── */

    /**
     * Uses FPDI (+ FPDF) to stamp the Sobat Medis logo on every page.
     * If FPDI is not installed, falls back to a DomPDF-generated watermark.
     */
    protected function overlayWatermark(string $inputPdf, string $outputPdf): void
    {
        if (class_exists(\setasign\Fpdi\Fpdi::class)) {
            $this->overlayWithFpdi($inputPdf, $outputPdf);
        } else {
            // Fallback: re-generate the PDF as a DomPDF page with background logo
            $this->overlayWithDompdf($inputPdf, $outputPdf);
        }
    }

    /**
     * Primary watermark method via FPDI.
     * The logo is placed center-page at ~42% of page width, 50% opacity.
     */
    protected function overlayWithFpdi(string $inputPdf, string $outputPdf): void
    {
        $fpdi = new \setasign\Fpdi\Fpdi();
        $fpdi->SetAutoPageBreak(false);

        $pageCount = $fpdi->setSourceFile($inputPdf);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $fpdi->importPage($i);
            $sz  = $fpdi->getTemplateSize($tpl);

            // Page dimensions in mm
            $w = $sz['width'];
            $h = $sz['height'];
            $orientation = $w > $h ? 'L' : 'P';

            $fpdi->AddPage($orientation, [$w, $h]);
            $fpdi->useTemplate($tpl, 0, 0, $w, $h);

            // Draw watermark logo if it exists
            if (file_exists($this->logoPath)) {
                $logoW = $w * $this->logoScale;           // e.g. ~42% of page width
                $logoH = $logoW;                          // square logo
                $x     = ($w - $logoW) / 2;              // center X
                $y     = ($h - $logoH) / 2;              // center Y

                // FPDF/FPDI does not support native image alpha natively.
                // We use the GD transparency trick: pre-process the PNG.
                $fadedLogo = $this->createFadedLogo($this->logoPath, $this->opacity);

                if ($fadedLogo) {
                    $fpdi->Image($fadedLogo, $x, $y, $logoW, $logoH, 'PNG');
                    @unlink($fadedLogo); // clean up temp faded logo
                }
            }
        }

        $fpdi->Output($outputPdf, 'F');
    }

    /**
     * Create a temporary PNG with the logo at the desired opacity using GD.
     * Returns the path to the temp file, or null on failure.
     */
    protected function createFadedLogo(string $logoPath, float $opacity): ?string
    {
        if (!extension_loaded('gd')) {
            return $logoPath; // GD not available; use original (fully opaque)
        }

        // Load the source image
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $src = match ($ext) {
            'png'  => @imagecreatefrompng($logoPath),
            'jpg',
            'jpeg' => @imagecreatefromjpeg($logoPath),
            'webp' => @imagecreatefromwebp($logoPath),
            default => null,
        };

        if (!$src) return null;

        $srcW = imagesx($src);
        $srcH = imagesy($src);

        // Create a true-color canvas with transparency
        $dst = imagecreatetruecolor($srcW, $srcH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        // Fill transparent background
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefill($dst, 0, 0, $transparent);

        // Copy source at reduced alpha
        imagecopymerge($dst, $src, 0, 0, 0, 0, $srcW, $srcH, (int) round($opacity * 100));

        imagedestroy($src);

        // Save to temp file
        $tmp = sys_get_temp_dir() . '/sobatmedis_watermark_' . uniqid() . '.png';
        imagepng($dst, $tmp);
        imagedestroy($dst);

        return $tmp;
    }

    /**
     * Fallback watermark when FPDI is unavailable.
     * Uses DomPDF to generate a new single-page PDF with logo as background.
     * NOTE: This does NOT preserve the original PDF content, just wraps it.
     */
    protected function overlayWithDompdf(string $inputPdf, string $outputPdf): void
    {
        $logoBase64 = '';
        if (file_exists($this->logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($this->logoPath));
        }

        $html = <<<HTML
        <html>
        <body style="margin:0;padding:0;position:relative;">
            <div style="position:relative;width:210mm;min-height:297mm;padding:20mm;">
                <img src="{$logoBase64}"
                     style="position:fixed;top:50%;left:50%;
                            transform:translate(-50%,-50%);
                            width:90mm;height:90mm;object-fit:contain;
                            opacity:0.5;z-index:9999;"
                     alt="Sobat Medis">
                <p style="color:#888;font-size:13px;text-align:center;margin-top:120mm;">
                    Dokumen ini dilindungi oleh Sobat Medis.<br>
                    Dilarang menyebarkan tanpa izin.
                </p>
            </div>
        </body>
        </html>
        HTML;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
        file_put_contents($outputPdf, $pdf->output());
    }

    /* ────────────────────────────────────────────────────
     |  HELPERS
     ─────────────────────────────────────────────────── */

    protected function ensureDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
