<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\Post;
use App\Models\StaffMember;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sections = HomepageSection::query()
            ->orderBy('position')
            ->get()
            ->filter(fn (HomepageSection $section) => $section->key === 'hero' || $section->is_visible)
            ->values();

        return view('pages.home', [
            'sections' => $sections,
            'homePosts' => Post::query()->visible()->orderByDesc('published_on')->orderByDesc('id')->limit(2)->get(),
            'homeGallery' => GalleryItem::query()->visible()->ordered()->get(),
            'homeStaff' => StaffMember::query()->visible()->ordered()->get(),
            'homeAgendas' => Agenda::nearest(2),
            'homeAchievements' => Achievement::query()->visible()->ordered()->get(),
        ]);
    }
}
