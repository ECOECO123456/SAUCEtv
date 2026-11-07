<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home / Catalog & Live Search Suggestions
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/suggestions', [MovieController::class, 'suggestions'])->name('suggestions');

// Movie Details & Reviews/Comments
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');

// Primary Show Route - Tumatanggap ng uri ($type) at $id para sa Watchlist at Catalog
Route::get('/show/{type}/{id}', [MovieController::class, 'show'])->name('show');

// Fallback Route kung sakaling ID lang ang maipasa
Route::get('/show/{id}', [MovieController::class, 'show']);

// Comments & Reviews Route
Route::post('/movie/{id}/comment', [MovieController::class, 'addComment'])->name('comment.add');
Route::get('/admin/comment/delete/{id}', [MovieController::class, 'deleteComment'])->name('admin.comment.delete');

// Watchlist Routes (Centralized Tab System & AJAX Operations)
Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
Route::post('/watchlist/store', [WatchlistController::class, 'store'])->name('watchlist.store');

// User Movie Requests & History
Route::get('/request-movie', [RequestController::class, 'create'])->name('movie.request.form');
Route::post('/request-movie', [RequestController::class, 'store'])->name('movie.request.store');
Route::get('/my-requests', [RequestController::class, 'history'])->name('user.requests.history');

// Admin Routes
Route::get('/admin/requests', [RequestController::class, 'adminRequests'])->name('admin.requests');
Route::get('/admin/movie/delete/{id}', [MovieController::class, 'destroy'])->name('admin.movie.delete');

// Authentication Routes (Login, Register, Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// TV-SHOWS EPISODES AJAX ROUTE
Route::get('/tv/{id}/season/{seasonNumber}/episodes', [MovieController::class, 'getTvEpisodes'])->name('tv.episodes');

//ADMIN REQUEST
use App\Http\Controllers\RequestController;
Route::post('/admin/requests/{id}/respond', [RequestController::class, 'respondToRequest'])
    ->name('admin.requests.respond');