<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use App\Models\Post;
use App\Models\SitePage;
use App\Models\Visit;
use App\Models\Visitor;
use App\Support\GeoPlace;
use App\Support\LocalDate;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class RecordPageView
{
    public const COOKIE = 'pengunjung';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldTrack($request)) {
            return $next($request);
        }

        $token = $request->cookie(self::COOKIE);
        $fresh = ! is_string($token) || ! preg_match('/^[a-f0-9]{40}$/', $token);

        if ($fresh) {
            $token = bin2hex(random_bytes(20));
        }

        $request->attributes->set('pengunjung_track', true);
        $request->attributes->set('pengunjung_token', $token);

        $response = $next($request);

        if ($response->isSuccessful()) {
            $visitor = Visitor::query()->firstOrCreate(
                ['token' => $token],
                [
                    'first_seen_at' => now(),
                    'last_seen_at' => now(),
                ],
            );

            if ($visitor->location_checked_at === null) {
                $place = GeoPlace::lookup($request->ip());
                $visitor->forceFill([
                    'country' => $place['country'] ?? null,
                    'region' => $place['region'] ?? null,
                    'city' => $place['city'] ?? null,
                    'location_checked_at' => now(),
                ])->save();
            }

            Visit::query()->firstOrCreate([
                'visitor_id' => $visitor->id,
                'visited_on' => LocalDate::today(),
            ]);

            PageView::query()->create([
                'visitor_id' => $visitor->id,
                'path' => '/'.ltrim($request->path(), '/'),
                'title' => $this->title($request),
                'viewed_at' => now(),
            ]);

            $visitor->forceFill(['last_seen_at' => now()])->save();
        }

        if ($fresh) {
            $response->headers->setCookie(cookie(
                self::COOKIE,
                $token,
                60 * 24 * 400,
                '/',
                null,
                $request->isSecure(),
                true,
                false,
                'lax',
            ));
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET') || $request->route() === null || ! $this->tablesReady()) {
            return false;
        }

        $purpose = strtolower((string) $request->header('Sec-Purpose', $request->header('Purpose')));

        if (str_contains($purpose, 'prefetch')) {
            return false;
        }

        $name = $request->route()?->getName();

        if (in_array($name, ['login', 'login.store', 'logout', 'unduhan.file'], true)) {
            return false;
        }

        return ! $request->is('admin', 'admin/*', 'login', 'logout', 'up', 'storage', 'storage/*');
    }

    private function tablesReady(): bool
    {
        static $ready = null;

        return $ready ??= Schema::hasTable('visitors') && Schema::hasTable('visits') && Schema::hasTable('page_views');
    }

    private function title(Request $request): string
    {
        $name = $request->route()?->getName();

        if ($name === 'berita.show') {
            $title = Post::query()->where('slug', (string) $request->route('slug'))->value('title');

            return is_string($title) && $title !== '' ? $title : 'Berita';
        }

        $pages = [
            'profil.sambutan' => SitePage::SAMBUTAN,
            'profil.sejarah' => SitePage::SEJARAH,
            'profil.struktur' => SitePage::STRUKTUR,
            'profil.visi' => SitePage::VISI,
            'spmb' => SitePage::SPMB,
        ];

        if (isset($pages[$name])) {
            $title = SitePage::query()->where('key', $pages[$name])->value('title');

            if (is_string($title) && $title !== '') {
                return $title;
            }
        }

        return match ($name) {
            'home' => 'Beranda',
            'berita' => 'Berita',
            'agenda' => 'Agenda',
            'pengumuman' => 'Pengumuman',
            'unduhan' => 'Unduhan',
            'prestasi' => 'Prestasi siswa',
            'galeri' => 'Galeri kegiatan',
            'fasilitas' => 'Fasilitas',
            'guru' => 'Guru dan staf',
            'kontak' => 'Kontak',
            'profil.sambutan' => 'Sambutan kepala',
            'profil.sejarah' => 'Sejarah',
            'profil.struktur' => 'Struktur organisasi',
            'profil.visi' => 'Visi dan misi',
            'spmb' => 'SPMB',
            default => 'Halaman',
        };
    }
}
