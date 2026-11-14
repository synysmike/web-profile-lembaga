<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Announcement;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Download;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\SiteLine;
use App\Models\SitePage;
use App\Models\StaffMember;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PublicPageController extends Controller
{
    public function sambutan()
    {
        return view('pages.sambutan', [
            'page' => SitePage::content(SitePage::SAMBUTAN),
        ]);
    }

    public function sejarah()
    {
        return view('pages.sejarah', [
            'page' => SitePage::content(SitePage::SEJARAH),
            'lines' => $this->lines(SitePage::SEJARAH, SiteLine::TRACE),
        ]);
    }

    public function struktur()
    {
        return view('pages.struktur', [
            'page' => SitePage::content(SitePage::STRUKTUR),
            'lines' => $this->lines(SitePage::STRUKTUR, SiteLine::ROLE),
        ]);
    }

    public function visi()
    {
        return view('pages.visi-misi', [
            'page' => SitePage::content(SitePage::VISI),
        ]);
    }

    public function berita()
    {
        $keyword = mb_substr(trim((string) request('q')), 0, 80);

        $posts = Post::query()
            ->visible()
            ->when($keyword !== '', fn (Builder $query) => $this->like($query, $keyword, ['title', 'excerpt']))
            ->orderByDesc('published_on')
            ->orderByDesc('id')
            ->get();

        $agendas = collect();
        $announcements = collect();

        if ($keyword !== '') {
            $agendaQuery = Agenda::query()->visible();
            $this->like($agendaQuery, $keyword, ['title', 'place']);
            $agendas = $agendaQuery->orderBy('happens_on')->get();

            $announcementQuery = Announcement::query()->visible()->ordered();
            $this->like($announcementQuery, $keyword, ['title', 'body']);
            $announcements = $announcementQuery->get();
        }

        return view('pages.berita', [
            'posts' => $posts,
            'agendas' => $agendas,
            'announcements' => $announcements,
            'keyword' => $keyword,
        ]);
    }

    public function beritaShow(string $slug)
    {
        $post = Post::query()->visible()->where('slug', $slug)->firstOrFail();

        return view('pages.berita-show', [
            'post' => $post,
        ]);
    }

    public function agenda()
    {
        return view('pages.agenda', [
            'agendas' => Agenda::nearest(),
        ]);
    }

    public function pengumuman()
    {
        return view('pages.pengumuman', [
            'announcements' => Announcement::query()->visible()->ordered()->get(),
        ]);
    }

    public function unduhan()
    {
        return view('pages.unduhan', [
            'downloads' => Download::query()->visible()->ordered()->get(),
        ]);
    }

    public function unduhanFile(Download $download)
    {
        abort_unless($download->is_visible, 404);
        abort_unless(is_string($download->file_path) && $download->file_path !== '' && Storage::disk('public')->exists($download->file_path), 404);

        return Storage::disk('public')->download(
            $download->file_path,
            (Str::slug($download->title) ?: 'unduhan').'.pdf',
        );
    }

    public function prestasi()
    {
        return view('pages.prestasi', [
            'achievements' => Achievement::query()->visible()->ordered()->get(),
        ]);
    }

    public function galeri()
    {
        return view('pages.galeri', [
            'items' => GalleryItem::query()->visible()->ordered()->get(),
        ]);
    }

    public function fasilitas()
    {
        return view('pages.fasilitas', [
            'facilities' => Facility::query()->visible()->ordered()->get(),
        ]);
    }

    public function guru()
    {
        return view('pages.guru', [
            'staff' => StaffMember::query()->visible()->ordered()->get(),
        ]);
    }

    public function spmb()
    {
        return view('pages.spmb', [
            'page' => SitePage::content(SitePage::SPMB),
            'steps' => $this->lines(SitePage::SPMB, SiteLine::STEP),
            'paths' => $this->lines(SitePage::SPMB, SiteLine::PATH),
        ]);
    }

    /**
     * @param  list<string>  $columns
     */
    private function like(Builder $query, string $keyword, array $columns): void
    {
        $like = '%'.addcslashes($keyword, '\\%_').'%';

        $query->where(function (Builder $inner) use ($like, $columns) {
            foreach ($columns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $inner->{$method}($column, 'like', $like);
            }
        });
    }

    private function lines(string $pageKey, string $kind)
    {
        return SiteLine::query()
            ->where('page_key', $pageKey)
            ->where('kind', $kind)
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }
}
