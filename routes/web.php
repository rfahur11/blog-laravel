<?php

use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Member\BlogController;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class,'index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //blog route
    Route::get('/member/blogs', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/member/blogs/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');

    Route::resource('/member/blogs', BlogController::class)->names([
        'index' => 'member.blogs.index',
        'edit' => 'member.blogs.edit',
        'update'=>'member.blogs.update',
        'create' => 'member.blogs.create',
        'store' => 'member.blogs.store',
        'destroy' => 'member.blogs.destroy'
    ])->parameters([
        'blogs' => 'post'
    ]);

});

require __DIR__.'/auth.php';
