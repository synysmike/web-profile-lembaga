<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\Visit;
use App\Support\LocalDate;
use App\Support\SchoolProfile;
use App\Support\SiteContent;
use App\Support\ThemeCatalog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $school = SchoolProfile::data();
        View::share('school', $school);
        View::share('schoolName', $school['name']);
        View::share('heroImageUrl', SiteContent::heroImageUrl());
        View::share('videotron', SiteContent::videotron());

        View::composer(['layouts.app', 'layouts.admin'], function ($view) {
            $view->with('activeTheme', ThemeCatalog::active());
        });

        View::composer('layouts.admin', function ($view) {
            $unread = 0;

            try {
                if (Schema::hasTable('contact_messages')) {
                    $unread = ContactMessage::query()->whereNull('read_at')->count();
                }
            } catch (Throwable) {
                $unread = 0;
            }

            $view->with('unreadMessages', $unread);
        });

        View::composer('partials.footer', function ($view) {
            $todayCount = 0;
            $total = 0;

            try {
                if (Schema::hasTable('visits')) {
                    $today = LocalDate::today();
                    $todayCount = Visit::query()->whereDate('visited_on', $today)->count();
                    $total = Visit::query()->count();

                    if (request()->attributes->get('pengunjung_track')) {
                        $token = (string) request()->attributes->get('pengunjung_token');
                        $known = Visit::query()
                            ->whereDate('visited_on', $today)
                            ->whereHas('visitor', fn ($query) => $query->where('token', $token))
                            ->exists();

                        if (! $known) {
                            $todayCount++;
                            $total++;
                        }
                    }
                }
            } catch (Throwable) {
                $todayCount = 0;
                $total = 0;
            }

            $view->with('visitorToday', $todayCount)->with('visitorTotal', $total);
        });
    }
}
