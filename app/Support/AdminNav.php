<?php

namespace App\Support;

class AdminNav
{
    /**
     * @return array<string, list<array{route: string, label: string, active: list<string>}>>
     */
    public static function groups(): array
    {
        return [
            'Situs' => [
                ['route' => 'admin.home', 'label' => 'Beranda', 'active' => ['admin.home']],
                ['route' => 'admin.content', 'label' => 'Konten dasar', 'active' => ['admin.content']],
                ['route' => 'admin.school', 'label' => 'Identitas', 'active' => ['admin.school']],
                ['route' => 'admin.account', 'label' => 'Akun', 'active' => ['admin.account']],
                ['route' => 'admin.visitors', 'label' => 'Pengunjung', 'active' => ['admin.visitors']],
            ],
            'Halaman' => [
                ['route' => 'admin.profile', 'label' => 'Profil', 'active' => ['admin.profile']],
                ['route' => 'admin.posts', 'label' => 'Berita', 'active' => ['admin.posts', 'admin.posts.*']],
                ['route' => 'admin.agenda', 'label' => 'Agenda', 'active' => ['admin.agenda']],
                ['route' => 'admin.announcements', 'label' => 'Pengumuman', 'active' => ['admin.announcements']],
                ['route' => 'admin.downloads', 'label' => 'Unduhan', 'active' => ['admin.downloads']],
                ['route' => 'admin.achievements', 'label' => 'Prestasi', 'active' => ['admin.achievements']],
                ['route' => 'admin.gallery', 'label' => 'Galeri', 'active' => ['admin.gallery']],
                ['route' => 'admin.facilities', 'label' => 'Fasilitas', 'active' => ['admin.facilities']],
                ['route' => 'admin.staff', 'label' => 'Guru dan staf', 'active' => ['admin.staff']],
                ['route' => 'admin.spmb', 'label' => 'SPMB', 'active' => ['admin.spmb']],
            ],
            'Kotak' => [
                ['route' => 'admin.messages', 'label' => 'Pesan kontak', 'active' => ['admin.messages']],
            ],
        ];
    }

    /**
     * @return list<array{route: string, label: string, active: list<string>}>
     */
    public static function items(): array
    {
        return array_merge(...array_values(self::groups()));
    }
}
