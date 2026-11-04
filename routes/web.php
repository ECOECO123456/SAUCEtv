<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;

// Public Catalog & Streaming
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');

// Live Search Autocomplete API Route
Route::get('/api/search-suggestions', [MovieController::class, 'suggestions'])->name('api.search.suggestions');

// Library (Guests redirected if not logged in)
Route::get('/library', function () {
    if (!session()->has('user')) {
        return redirect()->route('login')->with('error', 'Please log in to access your Library.');
    }
    return view('library');
})->name('library');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// User Movie Request Routes
Route::get('/request-movie', [MovieController::class, 'showRequestForm'])->name('movie.request.form');
Route::post('/request-movie', [MovieController::class, 'storeRequestMovie'])->name('movie.request.store');

// Admin Post & Notifications Routes
Route::get('/admin/requests', [MovieController::class, 'showAdminRequests'])->name('admin.requests');
Route::post('/admin/approve-request/{id}', [MovieController::class, 'approveRequest'])->name('admin.request.approve');
Route::get('/admin/delete-request/{id}', [MovieController::class, 'deleteRequest'])->name('admin.request.delete');
Route::get('/post-movie', [MovieController::class, 'showPostForm'])->name('movie.post.form');
Route::post('/post-movie', [MovieController::class, 'storePostMovie'])->name('movie.post.store');

// Interactive Comments & Admin Controls
Route::post('/movie/{id}/comment', [MovieController::class, 'addComment'])->name('comment.add');
Route::get('/admin/delete-comment/{id}', [MovieController::class, 'deleteComment'])->name('admin.comment.delete');
Route::get('/admin/delete-movie/{id}', [MovieController::class, 'deleteMovie'])->name('admin.movie.delete');

Route::get('/my-requests', function () {
    if (!session()->has('user')) {
        return redirect()->route('login');
    }
    return view('user_requests_history');
})->name('user.requests.history');
