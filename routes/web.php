<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminMediaListController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminSchoolController;
use App\Http\Controllers\AdminSpmbController;
use App\Http\Controllers\AdminTextListController;
use App\Http\Controllers\AdminVisitorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/profil/sambutan', [PublicPageController::class, 'sambutan'])->name('profil.sambutan');
Route::get('/profil/sejarah', [PublicPageController::class, 'sejarah'])->name('profil.sejarah');
Route::get('/profil/struktur', [PublicPageController::class, 'struktur'])->name('profil.struktur');
Route::get('/profil/visi-misi', [PublicPageController::class, 'visi'])->name('profil.visi');

Route::get('/berita', [PublicPageController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [PublicPageController::class, 'beritaShow'])->name('berita.show');

Route::get('/agenda', [PublicPageController::class, 'agenda'])->name('agenda');
Route::get('/pengumuman', [PublicPageController::class, 'pengumuman'])->name('pengumuman');
Route::get('/unduhan', [PublicPageController::class, 'unduhan'])->name('unduhan');
Route::get('/unduhan/{download}/berkas', [PublicPageController::class, 'unduhanFile'])->name('unduhan.file');
Route::get('/prestasi', [PublicPageController::class, 'prestasi'])->name('prestasi');
Route::get('/galeri', [PublicPageController::class, 'galeri'])->name('galeri');
Route::get('/fasilitas', [PublicPageController::class, 'fasilitas'])->name('fasilitas');
Route::get('/guru', [PublicPageController::class, 'guru'])->name('guru');

Route::view('/kontak', 'pages.kontak')->name('kontak');
Route::post('/kontak', [ContactController::class, 'store'])->name('kontak.store');

Route::get('/spmb', [PublicPageController::class, 'spmb'])->name('spmb');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminHomeController::class, 'edit'])->name('admin.home');
    Route::put('/admin', [AdminHomeController::class, 'update'])->name('admin.home.update');
    Route::get('/admin/konten', [AdminContentController::class, 'edit'])->name('admin.content');
    Route::put('/admin/konten', [AdminContentController::class, 'update'])->name('admin.content.update');
    Route::get('/admin/lembaga', [AdminSchoolController::class, 'edit'])->name('admin.school');
    Route::put('/admin/lembaga', [AdminSchoolController::class, 'update'])->name('admin.school.update');

    Route::get('/admin/profil', [AdminProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/admin/profil', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('/admin/berita', [AdminPostController::class, 'index'])->name('admin.posts');
    Route::get('/admin/berita/baru', [AdminPostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/berita', [AdminPostController::class, 'store'])->name('admin.posts.store');
    Route::get('/admin/berita/{post}/sunting', [AdminPostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/berita/{post}', [AdminPostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/admin/berita/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');

    Route::get('/admin/agenda', [AdminTextListController::class, 'editAgenda'])->name('admin.agenda');
    Route::post('/admin/agenda', [AdminTextListController::class, 'storeAgenda'])->name('admin.agenda.store');
    Route::put('/admin/agenda/{agenda}', [AdminTextListController::class, 'updateAgenda'])->name('admin.agenda.update');
    Route::delete('/admin/agenda/{agenda}', [AdminTextListController::class, 'destroyAgenda'])->name('admin.agenda.destroy');
    Route::get('/admin/pengumuman', [AdminTextListController::class, 'editAnnouncements'])->name('admin.announcements');
    Route::post('/admin/pengumuman', [AdminTextListController::class, 'storeAnnouncement'])->name('admin.announcements.store');
    Route::put('/admin/pengumuman/{announcement}', [AdminTextListController::class, 'updateAnnouncement'])->name('admin.announcements.update');
    Route::delete('/admin/pengumuman/{announcement}', [AdminTextListController::class, 'destroyAnnouncement'])->name('admin.announcements.destroy');
    Route::get('/admin/prestasi', [AdminTextListController::class, 'editAchievements'])->name('admin.achievements');
    Route::post('/admin/prestasi', [AdminTextListController::class, 'storeAchievement'])->name('admin.achievements.store');
    Route::put('/admin/prestasi/{achievement}', [AdminTextListController::class, 'updateAchievement'])->name('admin.achievements.update');
    Route::delete('/admin/prestasi/{achievement}', [AdminTextListController::class, 'destroyAchievement'])->name('admin.achievements.destroy');
    Route::get('/admin/fasilitas', [AdminTextListController::class, 'editFacilities'])->name('admin.facilities');
    Route::post('/admin/fasilitas', [AdminTextListController::class, 'storeFacility'])->name('admin.facilities.store');
    Route::put('/admin/fasilitas/{facility}', [AdminTextListController::class, 'updateFacility'])->name('admin.facilities.update');
    Route::delete('/admin/fasilitas/{facility}', [AdminTextListController::class, 'destroyFacility'])->name('admin.facilities.destroy');

    Route::get('/admin/galeri', [AdminMediaListController::class, 'editGallery'])->name('admin.gallery');
    Route::post('/admin/galeri', [AdminMediaListController::class, 'storeGallery'])->name('admin.gallery.store');
    Route::put('/admin/galeri/{item}', [AdminMediaListController::class, 'updateGallery'])->name('admin.gallery.update');
    Route::delete('/admin/galeri/{item}', [AdminMediaListController::class, 'destroyGallery'])->name('admin.gallery.destroy');
    Route::get('/admin/guru', [AdminMediaListController::class, 'editStaff'])->name('admin.staff');
    Route::post('/admin/guru', [AdminMediaListController::class, 'storeStaff'])->name('admin.staff.store');
    Route::put('/admin/guru/{staff}', [AdminMediaListController::class, 'updateStaff'])->name('admin.staff.update');
    Route::delete('/admin/guru/{staff}', [AdminMediaListController::class, 'destroyStaff'])->name('admin.staff.destroy');
    Route::get('/admin/akun', [AdminAccountController::class, 'edit'])->name('admin.account');
    Route::put('/admin/akun', [AdminAccountController::class, 'update'])->name('admin.account.update');
    Route::get('/admin/unduhan', [AdminMediaListController::class, 'editDownloads'])->name('admin.downloads');
    Route::post('/admin/unduhan', [AdminMediaListController::class, 'storeDownload'])->name('admin.downloads.store');
    Route::put('/admin/unduhan/{download}', [AdminMediaListController::class, 'updateDownload'])->name('admin.downloads.update');
    Route::delete('/admin/unduhan/{download}', [AdminMediaListController::class, 'destroyDownload'])->name('admin.downloads.destroy');

    Route::get('/admin/spmb', [AdminSpmbController::class, 'edit'])->name('admin.spmb');
    Route::put('/admin/spmb', [AdminSpmbController::class, 'update'])->name('admin.spmb.update');
    Route::post('/admin/spmb/tahap', [AdminSpmbController::class, 'storeStep'])->name('admin.spmb.steps.store');
    Route::put('/admin/spmb/tahap/{line}', [AdminSpmbController::class, 'updateStep'])->name('admin.spmb.steps.update');
    Route::delete('/admin/spmb/tahap/{line}', [AdminSpmbController::class, 'destroyStep'])->name('admin.spmb.steps.destroy');
    Route::post('/admin/spmb/jalur', [AdminSpmbController::class, 'storePath'])->name('admin.spmb.paths.store');
    Route::put('/admin/spmb/jalur/{line}', [AdminSpmbController::class, 'updatePath'])->name('admin.spmb.paths.update');
    Route::delete('/admin/spmb/jalur/{line}', [AdminSpmbController::class, 'destroyPath'])->name('admin.spmb.paths.destroy');

    Route::get('/admin/pesan', [AdminMessageController::class, 'index'])->name('admin.messages');
    Route::put('/admin/pesan/{message}/baca', [AdminMessageController::class, 'read'])->name('admin.messages.read');
    Route::delete('/admin/pesan/{message}', [AdminMessageController::class, 'destroy'])->name('admin.messages.destroy');

    Route::get('/admin/pengunjung', [AdminVisitorController::class, 'index'])->name('admin.visitors');
});
