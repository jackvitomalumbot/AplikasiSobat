<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\MahasiswaDetail;
use App\Models\Pertemuan;
use App\Models\PengajarDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* ─── Admin ─── */
        User::create([
            'nama' => 'Admin SobatMedis',
            'email' => 'admin@sobatmedis.com',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        /* ─── Pengajar ─── */
        $pengajarData = [
            ['Dr. Sari Kusuma', 'sari@sobatmedis.com', 'Anatomi & Fisiologi', 'Spesialis anatomi dengan 10 tahun pengalaman.'],
            ['Dr. Andi Pratama', 'andi@sobatmedis.com', 'Farmakologi Klinis', 'Pakar farmakologi klinik dari RS Harapan Kita.'],
            ['Dr. Budi Santoso', 'budi@sobatmedis.com', 'Bedah Umum', 'Konsultan bedah dengan lebih dari 500 operasi.'],
            ['Dr. Rina Wati', 'rina@sobatmedis.com', 'Patologi', 'Dosen patologi di FK Universitas Indonesia.'],
            ['Dr. Hasan Ali', 'hasan@sobatmedis.com', 'Mikrobiologi', 'Peneliti mikrobiologi tropis.'],
            ['Dr. Maya Sari', 'maya@sobatmedis.com', 'Kardiologi', 'Fellow kardiologi intervensi.'],
        ];

        $pengajarUsers = [];
        foreach ($pengajarData as $p) {
            $user = User::create([
                'nama' => $p[0],
                'email' => $p[1],
                'password' => 'password',
                'role' => 'pengajar',
                'email_verified_at' => now(),
            ]);
            PengajarDetail::create([
                'user_id' => $user->id,
                'spesialisasi' => $p[2],
                'bio' => $p[3],
            ]);
            $pengajarUsers[] = $user;
        }

        /* ─── Kelas ─── */
        $kelasData = [
            [0, 'Anatomi Dasar Semester 1', 150000, 'Mempelajari dasar-dasar anatomi tubuh manusia mulai dari sistem muskuloskeletal hingga organ dalam.'],
            [0, 'Histologi Klinis', 200000, 'Pemahaman mendalam tentang jaringan tubuh manusia dan kaitannya dengan klinis.'],
            [1, 'Farmakologi Dasar', 175000, 'Prinsip-prinsip dasar farmakologi termasuk farmakokinetik dan farmakodinamik.'],
            [1, 'Farmakoterapi Penyakit Kronik', 250000, 'Terapi farmakologis untuk penyakit-penyakit kronik seperti diabetes dan hipertensi.'],
            [2, 'Teknik Bedah Minor', 300000, 'Pengenalan teknik bedah minor untuk mahasiswa kedokteran tahap klinis.'],
            [3, 'Patologi Sistemik', 180000, 'Patologi penyakit-penyakit sistemik seperti kardiovaskular, respirasi, dan gastrointestinal.'],
            [4, 'Mikrobiologi Medis', 160000, 'Bakteri, virus, jamur, dan parasit yang berperan dalam penyakit infeksi.'],
            [5, 'EKG Interpretasi', 220000, 'Belajar membaca dan menginterpretasi hasil EKG secara sistematis.'],
        ];

        $kelasModels = [];
        foreach ($kelasData as $k) {
            $kelas = Kelas::create([
                'pengajar_id' => $pengajarUsers[$k[0]]->id,
                'nama_kelas' => $k[1],
                'harga' => $k[2],
                'deskripsi' => $k[3],
            ]);

            // Add sample pertemuan
            Pertemuan::create([
                'kelas_id' => $kelas->id,
                'judul' => 'Pendahuluan ' . $k[1],
                'deskripsi' => 'Perkenalan materi dan overview semester.',
                'tanggal' => now()->addDays(rand(1, 7)),
                'tipe' => 'pertemuan',
            ]);

            Pertemuan::create([
                'kelas_id' => $kelas->id,
                'judul' => 'Tugas 1 — ' . $k[1],
                'deskripsi' => 'Tugas pertama untuk mengevaluasi pemahaman awal.',
                'tanggal' => now()->addDays(rand(8, 14)),
                'tipe' => 'tugas',
                'deadline' => now()->addDays(rand(15, 21)),
                'instruksi_tugas' => 'Buatkan rangkuman materi pertemuan 1 dalam format PDF. Minimal 2 halaman.',
            ]);

            $kelasModels[] = $kelas;
        }

        /* ─── Mahasiswa ─── */
        $mahasiswaData = [
            ['Ahmad Fauzi', 'ahmad@student.ui.ac.id', 'Universitas Indonesia', '2101001'],
            ['Dina Puspita', 'dina@student.ugm.ac.id', 'Universitas Gadjah Mada', '2102002'],
            ['Eko Saputra', 'eko@student.unair.ac.id', 'Universitas Airlangga', '2103003'],
            ['Fitri Handayani', 'fitri@student.undip.ac.id', 'Universitas Diponegoro', '2104004'],
            ['Gilang Ramadhan', 'gilang@student.its.ac.id', 'Institut Teknologi Sepuluh November', '2105005'],
        ];

        foreach ($mahasiswaData as $i => $m) {
            $user = User::create([
                'nama' => $m[0],
                'email' => $m[1],
                'password' => 'password',
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]);
            MahasiswaDetail::create([
                'user_id' => $user->id,
                'universitas' => $m[2],
                'nim' => $m[3],
            ]);

            // Enroll in 2-3 random classes
            $enrollKelas = collect($kelasModels)->random(rand(2, 3));
            foreach ($enrollKelas as $ek) {
                Enrollment::create([
                    'mahasiswa_id' => $user->id,
                    'kelas_id' => $ek->id,
                    'payment_status' => 'paid',
                    'payment_id' => 'SEED-' . strtoupper(uniqid()),
                ]);
            }
        }
    }
}
