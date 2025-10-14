<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\CommentAdminController;
use Illuminate\Support\Facades\Route;

// Auth Breeze
require __DIR__.'/auth.php';

// Accueil
Route::get('/', [PostController::class, 'index'])->name('home');

// Page article (binding par slug)
Route::get('/article/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact');

// Commentaires (réservé aux connectés)
Route::middleware('auth')->group(function () {
    Route::post('/article/{post:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Mon compte (Breeze fournit /profile : on peut aliaser /account)
    Route::view('/account', 'profile.partials.update-profile-information-form')->name('account');
});

// Admin (modération + gestion contenu)
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/comments', [CommentAdminController::class, 'index'])->name('comments.index');
    Route::post('/comments/{comment}/approve', [CommentAdminController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{comment}/hide', [CommentAdminController::class, 'hide'])->name('comments.hide');
    Route::delete('/comments/{comment}', [CommentAdminController::class, 'destroy'])->name('comments.destroy');

    // À compléter : Posts CRUD (créer/éditer/publier/dépublier),
    // catégories/tags CRUD, page Contact CRUD (PageAdminController)
});
