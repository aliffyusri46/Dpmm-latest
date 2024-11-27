<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;

//Route for BlogController
// Route::get('/', [BlogController::class, 'index'])->name('index');
// Route::get('/blogs/create', [BlogController::class, 'create'])->name('create');
// Route::post('/blogs', [BlogController::class, 'store'])->name('store');
// Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('show');
// Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('edit');
// Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('update');
// Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('destroy');

// Route::resource('blogs', BlogController::class)->except(['index']);

// //Route for CommentController
// Route::get('/blogs/{blog}/comments', [CommentController::class, 'index'])->name('comments.index');
// Route::post('/blogs/{blog}/comments', [CommentController::class, 'store'])->name('comments.store');
// Route::delete('/blogs/{blog}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');


// Route::get('/login', function () {
//     return Inertia::render('Login');
// })->name('login');


Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::resource('blogs', BlogController::class)->except(['index']);
    //Route for CommentController
    Route::get('/blogs/{blog}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/blogs/{blog}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/blogs/{blog}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
