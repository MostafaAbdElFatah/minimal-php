<?php

use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;

Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/welcome', 'welcome', [
    'greeting' => 'Hello, ',
    'name' => request('name', 'Guest'),
    'tasks' => ['Task 1', 'Task 2', 'Task 3'],
]);

// ==============================================================
//       Ideas routes
// ==============================================================

// Route::delete('/delete-ideas', function () {
//     session()->forget('ideas');
//     return redirect('/')->with('status', 'All ideas deleted successfully!');
// });
Route::get('/', [IdeaController::class, 'index']);
Route::get('/ideas/create', [IdeaController::class, 'create']);
Route::post('/ideas/create', [IdeaController::class, 'store']);
Route::get('/ideas/{idea}', [IdeaController::class, 'show']);
Route::get('/ideas/{idea}/edit', [IdeaController::class, 'edit']);
Route::put('/ideas/{idea}', [IdeaController::class, 'update']);
Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy']);
Route::delete('/ideas', [IdeaController::class, 'destroyAll'])->name('ideas.destroy-all');
