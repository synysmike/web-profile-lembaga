<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Download;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\SiteLine;
use App\Models\SitePage;
use App\Models\StaffMember;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->page(SitePage::SAMBUTAN, [
            'title' => 'Sambutan kepala madrasah',
            'summary' => 'Ir. Teguh Wibowo, M.Pd.',
            'body' => "Assalamu alaikum warahmatullahi wabarakatuh.\n\nMTs Nurul Ilmi ingin menjadi tempat siswa belajar dengan tenang, berbicara dengan sopan, dan berani mencoba hal yang bermanfaat. Kami menjaga ritme harian: salam, belajar, ibadah, dan pulang dengan kabar yang jelas untuk orang tua.\n\nHalaman ini adalah teks contoh pada template. Nama, tanggal, dan kegiatan bisa diganti saat madrasah mengisi profil yang sebenarnya.",
        ]);

        $this->page(SitePage::SEJARAH, [
            'title' => 'Sejarah',
            'summary' => 'Jejak singkat berdirinya madrasah, ditulis sebagai contoh template.',
        ]);
        $this->line(SitePage::SEJARAH, SiteLine::TRACE, 1, [
            'label' => '2008',
            'body' => 'Belajar dimulai di ruang serbaguna dengan dua rombongan belajar.',
        ]);
        $this->line(SitePage::SEJARAH, SiteLine::TRACE, 2, [
            'label' => '2015',
            'body' => 'Gedung kelas dua lantai dan perpustakaan kecil mulai dipakai.',
        ]);
        $this->line(SitePage::SEJARAH, SiteLine::TRACE, 3, [
            'label' => '2024',
            'body' => 'Kegiatan siswa rutin tercatat di agenda dan papan pengumuman.',
        ]);

        $this->page(SitePage::STRUKTUR, [
            'title' => 'Struktur organisasi',
            'summary' => 'Susunan pimpinan contoh untuk tahun ajaran berjalan.',
        ]);
        $this->line(SitePage::STRUKTUR, SiteLine::ROLE, 1, [
            'label' => 'Kepala madrasah',
            'title' => 'Hasan Basri, M.Pd.',
        ]);
        $this->line(SitePage::STRUKTUR, SiteLine::ROLE, 2, [
            'label' => 'Wakil kurikulum',
            'title' => 'Siti Rahma, S.Pd.',
        ]);
        $this->line(SitePage::STRUKTUR, SiteLine::ROLE, 3, [
            'label' => 'Wakil kesiswaan',
            'title' => 'Ahmad Fauzi, S.Pd.',
        ]);

        $this->page(SitePage::VISI, [
            'title' => 'Visi dan misi',
            'summary' => 'Arah madrasah yang dipakai sebagai contoh pada template ini.',
            'body' => 'Menjadi madrasah yang menumbuhkan iman, ilmu, dan kebiasaan baik sampai siswa berani belajar sendiri.',
            'missions' => "Menjaga salat berjamaah dan adab di kelas.\nMengajar dengan tugas yang jelas dan umpan balik yang cepat.\nMengajak orang tua memantau kehadiran dan perkembangan anak.",
            'goals' => "Siswa naik kelas dengan nilai yang jujur.\nSetiap kelas punya karya yang ditampilkan setahun sekali.\nWarga madrasah mendapat informasi dari satu situs.",
        ]);

        $this->page(SitePage::SPMB, [
            'title' => 'SPMB 2026/2027',
            'summary' => 'Penerimaan murid baru. Baca alur dan jalur sebelum datang ke madrasah.',
        ]);
        $this->line(SitePage::SPMB, SiteLine::STEP, 1, [
            'label' => '1–20 Juni 2026',
            'title' => 'Pendaftaran berkas',
            'body' => 'Calon siswa menyiapkan rapor, kartu keluarga, dan pas foto.',
        ]);
        $this->line(SitePage::SPMB, SiteLine::STEP, 2, [
            'label' => '24 Juni 2026',
            'title' => 'Pengumuman',
            'body' => 'Hasil ditempel di papan pengumuman dan di halaman ini.',
        ]);
        $this->line(SitePage::SPMB, SiteLine::PATH, 1, [
            'title' => 'Domisili',
            'body' => 'Untuk calon siswa yang tinggal di wilayah terdekat.',
        ]);
        $this->line(SitePage::SPMB, SiteLine::PATH, 2, [
            'title' => 'Prestasi',
            'body' => 'Untuk rapor atau piagam lomba yang masih berlaku.',
        ]);
        $this->line(SitePage::SPMB, SiteLine::PATH, 3, [
            'title' => 'Afirmasi',
            'body' => 'Untuk keluarga pemegang kartu bantuan yang sah.',
        ]);
        $this->line(SitePage::SPMB, SiteLine::PATH, 4, [
            'title' => 'Perpindahan',
            'body' => 'Untuk anak guru atau keluarga yang pindah tugas.',
        ]);

        Post::query()->firstOrCreate(['slug' => 'penilaian-akhir-semester-berlangsung-tertib'], [
            'title' => 'Penilaian akhir semester berlangsung tertib',
            'published_on' => '2026-11-12',
            'excerpt' => 'Siswa kelas 7, 8, dan 9 mengikuti penilaian dengan pengawasan guru wali.',
            'body' => "Penilaian akhir semester di MTs Nurul Ilmi diikuti siswa kelas 7, 8, dan 9. Guru wali mengawasi ruang ujian dan mengumpulkan lembar jawaban di hari yang sama.\n\nKepala madrasah mengingatkan siswa untuk mengerjakan sesuai kemampuan sendiri. Hasil penilaian dibagikan lewat rapor tengah semester pada agenda November.",
            'is_visible' => true,
        ]);
        Post::query()->firstOrCreate(['slug' => 'gawai-dipakai-seperlunya-saat-belajar'], [
            'title' => 'Gawai dipakai seperlunya saat belajar',
            'published_on' => '2026-11-02',
            'excerpt' => 'Madrasah mengajak orang tua mendampingi siswa memakai gawai untuk tugas.',
            'body' => "Madrasah meminta gawai dipakai untuk tugas yang memang membutuhkan sumber daring. Di luar itu, siswa menyimpan gawai di dalam tas selama pelajaran berlangsung.\n\nOrang tua diajak meninjau riwayat pemakaian di rumah. Guru kelas menerima laporan jika ada tugas yang harus dikumpulkan lewat tautan.",
            'is_visible' => true,
        ]);

        Agenda::query()->firstOrCreate(['title' => 'Pembagian rapor tengah semester'], [
            'happens_on' => '2026-11-18',
            'place' => 'Aula lantai satu, pukul 08.00',
            'is_visible' => true,
        ]);
        Agenda::query()->firstOrCreate(['title' => 'Lomba pidato kelas 8'], [
            'happens_on' => '2026-12-02',
            'place' => 'Ruang serbaguna, pukul 09.00',
            'is_visible' => true,
        ]);

        $this->placed(Announcement::class, 'Seragam upacara', [
            'body' => 'Setiap Senin siswa memakai seragam lengkap dan membawa topi.',
        ]);
        $this->placed(Announcement::class, 'Libur guru', [
            'body' => 'Pada Jumat pekan ini tidak ada pelajaran tambahan setelah zuhur.',
        ]);

        Download::query()->firstOrCreate(['title' => 'Kalender akademik 2026/2027'], [
            'position' => 1,
            'is_visible' => true,
        ]);
        Download::query()->firstOrCreate(['title' => 'Tata tertib siswa'], [
            'position' => 2,
            'is_visible' => true,
        ]);

        $this->placed(Achievement::class, 'Pidato bahasa Arab tingkat kecamatan', [
            'rank' => 'Juara 1',
        ]);
        $this->placed(Achievement::class, 'Cerdas cermat IPA tingkat kota', [
            'rank' => 'Juara 2',
        ]);
        $this->placed(Achievement::class, 'Kaligrafi pekan seni pelajar', [
            'rank' => 'Harapan 1',
        ]);

        $this->placed(GalleryItem::class, 'Upacara Senin', []);
        $this->placed(GalleryItem::class, 'Latihan pidato', []);
        $this->placed(GalleryItem::class, 'Kerja bakti', []);

        $this->placed(Facility::class, 'Ruang kelas', [
            'description' => 'Delapan ruang dengan papan tulis dan meja kelompok.',
        ]);
        $this->placed(Facility::class, 'Perpustakaan', [
            'description' => 'Koleksi pelajaran, novel remaja, dan kamus.',
        ]);
        $this->placed(Facility::class, 'Musala', [
            'description' => 'Dipakai untuk salat zuhur berjamaah.',
        ]);
        $this->placed(Facility::class, 'Lapangan', [
            'description' => 'Upacara, olahraga, dan kerja bakti.',
        ]);

        $this->named('Hasan Basri, M.Pd.', 'Kepala madrasah');
        $this->named('Siti Rahma, S.Pd.', 'Wakil kurikulum');
        $this->named('Ahmad Fauzi, S.Pd.', 'Wakil kesiswaan');
        $this->named('Nur Aisyah, S.Pd.', 'Guru Bahasa Indonesia');
    }

    private function page(string $key, array $values): void
    {
        SitePage::query()->firstOrCreate(['key' => $key], $values);
    }

    private function line(string $pageKey, string $kind, int $position, array $values): void
    {
        $match = [
            'page_key' => $pageKey,
            'kind' => $kind,
            'position' => $position,
        ];

        SiteLine::query()->firstOrCreate($match, $values);
    }

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $class
     * @param  array<string, mixed>  $values
     */
    private function placed(string $class, string $title, array $values): void
    {
        $position = (int) $class::query()->count() + 1;
        $class::query()->firstOrCreate(['title' => $title], $values + [
            'position' => $position,
            'is_visible' => true,
        ]);
    }

    private function named(string $name, string $role): void
    {
        $position = (int) StaffMember::query()->count() + 1;
        StaffMember::query()->firstOrCreate(['name' => $name], [
            'role' => $role,
            'position' => $position,
            'is_visible' => true,
        ]);
    }
}
