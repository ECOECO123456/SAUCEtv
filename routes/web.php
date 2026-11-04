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
Route::post('/movie/{id}/comment', [MovieController::class, 'storeComment'])->name('comment.add');

// Watchlist Routes
Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
Route::get('/watchlist/plan', [WatchlistController::class, 'plan'])->name('watchlist.plan');
Route::get('/watchlist/watched', [WatchlistController::class, 'watched'])->name('watchlist.watched');
Route::post('/watchlist/store', [WatchlistController::class, 'store'])->name('watchlist.store'); // <-- Idagdag ito

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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');