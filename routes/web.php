<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\CommentAdminController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsAdmin; // middleware admin (classe)

// --- Auth Breeze
require __DIR__.'/auth.php';

// --- Public
Route::get('/', [PostController::class, 'index'])->name('home');

// Page article (binding par slug)
Route::get('/article/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Contact (page + envoi)
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// --- Espace authentifié (profil + commentaires)
Route::middleware('auth')->group(function () {
    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alias /account
    Route::get('/account', [ProfileController::class, 'edit'])->name('account');

    // Commentaires
    Route::post('/article/{post:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// --- Admin (modération + gestion contenu)
Route::middleware(['auth', EnsureUserIsAdmin::class])
    ->prefix('admin')->name('admin.')->group(function () {
        // Modération commentaires
        Route::get('/comments', [CommentAdminController::class, 'index'])->name('comments.index');
        Route::post('/comments/{comment}/approve', [CommentAdminController::class, 'approve'])->name('comments.approve');
        Route::post('/comments/{comment}/hide', [CommentAdminController::class, 'hide'])->name('comments.hide');
        Route::delete('/comments/{comment}', [CommentAdminController::class, 'destroy'])->name('comments.destroy');

        // Articles : liste, brouillons, créer, éditer, MAJ, publier/dépublier, supprimer
        Route::get('/posts',             [PostAdminController::class, 'index'])->name('posts.index');      // tous
        Route::get('/posts/drafts',      [PostAdminController::class, 'drafts'])->name('posts.drafts');    // brouillons
        Route::get('/posts/create',      [PostAdminController::class, 'create'])->name('posts.create');
        Route::post('/posts',            [PostAdminController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [PostAdminController::class, 'edit'])->name('posts.edit');
        Route::patch('/posts/{post}',    [PostAdminController::class, 'update'])->name('posts.update');
        Route::post('/posts/{post}/publish',   [PostAdminController::class, 'publish'])->name('posts.publish');
        Route::post('/posts/{post}/unpublish', [PostAdminController::class, 'unpublish'])->name('posts.unpublish');
        Route::delete('/posts/{post}',         [PostAdminController::class, 'destroy'])->name('posts.destroy');
    });
