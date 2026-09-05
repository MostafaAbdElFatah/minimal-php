<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $ideas = session()->get('ideas', []);
    return view('ideas.index', [
        'ideas' => $ideas,
    ]);
});

Route::post('/ideas', function () {
    $idea = request('description');
    // Handle form submission logic here
    session()->push('ideas', $idea);
    return redirect('/')->with('success', 'Idea submitted successfully!');
});

Route::get('/delete-ideas', function () {
    session()->forget('ideas');
    return redirect('/')->with('success', 'All ideas deleted successfully!');
});

Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/welcome', 'welcome', [
    'greeting' => 'Hello, ',
    'name' => request('name', 'Guest'),
    'tasks' => ['Task 1', 'Task 2', 'Task 3'],
]);
