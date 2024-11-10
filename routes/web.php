<?php

use App\Models\Task;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\task\TaskController;
use App\Http\Controllers\task\TaskDemoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
   // return view('layout.app2');
    //return view('pages.home');
    return view('layout.app3');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('task-demo', TaskDemoController::class);
    Route::resource('tasks', TaskController::class);


    // Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    // // Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index')->middleware('can:viewAny,App\Models\Task');
    // Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    // Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    // Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    // // Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    // Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit')->can('update', 'task');
    // Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    // Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

require __DIR__ . '/auth.php';
