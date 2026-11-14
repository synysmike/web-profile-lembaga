<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'kicker', 'title', 'subtitle', 'excerpt', 'position', 'is_visible'])]
class HomepageSection extends Model
{
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function label(): string
    {
        return match ($this->key) {
            'hero' => 'Hero',
            'pencarian' => 'Pencarian',
            'berita' => 'Berita',
            'sambutan' => 'Sambutan',
            'galeri' => 'Galeri',
            'layanan' => 'Layanan',
            'guru' => 'Guru dan staf',
            'agenda' => 'Agenda',
            'prestasi' => 'Prestasi',
            default => $this->key,
        };
    }

    /**
     * @return list<array{key: string, kicker?: string, title: string, subtitle: string|null, excerpt: string|null, position: int, is_visible: bool}>
     */
    public static function defaults(): array
    {
        return [
            [
                'key' => 'hero',
                'kicker' => 'Madrasah Tsanawiyah',
                'title' => 'MTs Nurul Ilmi',
                'subtitle' => 'Beriman, Berilmu, Beramal',
                'excerpt' => 'Mari bergabung menuju madrasah yang tertib, ceria, dan siap berprestasi.',
                'position' => 1,
                'is_visible' => true,
            ],
            [
                'key' => 'pencarian',
                'title' => 'Cari informasi',
                'subtitle' => null,
                'excerpt' => 'Temukan berita, agenda, dan pengumuman madrasah dari satu kotak pencarian.',
                'position' => 2,
                'is_visible' => true,
            ],
            [
                'key' => 'berita',
                'title' => 'Berita terbaru',
                'subtitle' => null,
                'excerpt' => 'Kabar kegiatan yang baru dipublikasikan untuk siswa dan orang tua.',
                'position' => 3,
                'is_visible' => true,
            ],
            [
                'key' => 'sambutan',
                'title' => 'Sambutan kepala madrasah',
                'subtitle' => 'Ustadz Hasan Basri, M.Pd.',
                'excerpt' => 'Assalamu alaikum. Madrasah ini tumbuh dari kebiasaan belajar yang tenang, sopan, dan saling mengingatkan.',
                'position' => 4,
                'is_visible' => true,
            ],
            [
                'key' => 'galeri',
                'title' => 'Galeri kegiatan',
                'subtitle' => null,
                'excerpt' => 'Sebagian momen belajar, ibadah, dan perayaan di lingkungan madrasah.',
                'position' => 5,
                'is_visible' => true,
            ],
            [
                'key' => 'layanan',
                'title' => 'Layanan',
                'subtitle' => null,
                'excerpt' => 'Jalan cepat menuju informasi yang paling sering dicari warga madrasah.',
                'position' => 6,
                'is_visible' => true,
            ],
            [
                'key' => 'guru',
                'title' => 'Guru dan staf',
                'subtitle' => null,
                'excerpt' => 'Orang-orang yang mendampingi siswa setiap hari di kelas dan di luar kelas.',
                'position' => 7,
                'is_visible' => true,
            ],
            [
                'key' => 'agenda',
                'title' => 'Agenda mendatang',
                'subtitle' => null,
                'excerpt' => 'Jadwal singkat yang perlu diketahui siswa dan orang tua.',
                'position' => 8,
                'is_visible' => true,
            ],
            [
                'key' => 'prestasi',
                'title' => 'Prestasi siswa',
                'subtitle' => null,
                'excerpt' => 'Hasil latihan yang sudah diuji di luar madrasah.',
                'position' => 9,
                'is_visible' => true,
            ],
        ];
    }
}
