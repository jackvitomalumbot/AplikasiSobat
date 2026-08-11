<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class InfoController extends Controller
{
    public function index()
    {
        /* ── 01: Prestasi Terbaru ── */
        $prestasiUtama    = Prestasi::where('aktif', true)->where('tipe', 'featured') ->orderBy('urutan')->get();
        $prestasiMahasiswa = Prestasi::where('aktif', true)->where('tipe', 'mahasiswa')->orderBy('urutan')->get();
        $prestasiPengajar  = Prestasi::where('aktif', true)->where('tipe', 'pengajar') ->orderBy('urutan')->get();

        /* ── 02: WHO Latest News (cached 24 jam) ── */
        $whoNews = Cache::remember('who_news_24h', 60 * 60 * 24, function () {
            return $this->fetchWhoNews();
        });

        /* ── 03: Sobat Medis Update — kelas terbaru ── */
        $kelasBarru = Kelas::where('is_active', true)
            ->with(['pengajar'])
            ->latest()
            ->take(6)
            ->get();

        /* ── 04: Community Highlight ── */
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalPengajar  = User::where('role', 'pengajar')->count();
        $totalKelas     = Kelas::where('is_active', true)->count();

        return view('info', compact(
            'prestasiUtama',
            'prestasiMahasiswa',
            'prestasiPengajar',
            'whoNews',
            'kelasBarru',
            'totalMahasiswa',
            'totalPengajar',
            'totalKelas'
        ));
    }

    /* ── WHO RSS Fetcher ── */
    private function fetchWhoNews(): array
    {
        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'SobatMedis/1.0 (educational platform; hello@sobatmedis.id)',
                    'Accept'     => 'application/rss+xml, application/xml, text/xml, */*',
                ])
                ->get('https://www.who.int/rss-feeds/news-advisories-full.xml');

            if (!$response->successful()) {
                return [];
            }

            $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$xml) return [];

            $items = [];
            $count = 0;
            foreach ($xml->channel->item as $item) {
                if ($count >= 3) break;
                $items[] = [
                    'title'       => (string) $item->title,
                    'link'        => (string) $item->link,
                    'description' => strip_tags(html_entity_decode((string) $item->description)),
                    'pubDate'     => (string) $item->pubDate,
                    'pubDateFormatted' => $this->formatDate((string) $item->pubDate),
                ];
                $count++;
            }
            return $items;
        } catch (\Exception $e) {
            \Log::warning('[InfoController] WHO RSS fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    private function formatDate(string $dateStr): string
    {
        try {
            $dt = new \DateTime($dateStr);
            return $dt->format('d M Y');
        } catch (\Exception) {
            return $dateStr;
        }
    }
}
