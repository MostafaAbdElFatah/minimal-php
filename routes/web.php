<?php

use App\Models\Idea;
use Illuminate\Support\Facades\Route;


Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/welcome', 'welcome', [
    'greeting' => 'Hello, ',
    'name' => request('name', 'Guest'),
    'tasks' => ['Task 1', 'Task 2', 'Task 3'],
]);

// Route::delete('/delete-ideas', function () {
//     session()->forget('ideas');
//     return redirect('/')->with('success', 'All ideas deleted successfully!');
// });

Route::get('/', function () {
    //session()->get('ideas', []);
    //$ideas = DB::table('ideas')->get();
    //$ideas = Idea::all()

    $ideas = Idea::query()
    ->when(request('state'), function ($query, $state) {
        $query->where('state', $state);
    })
    ->get();

    return view('ideas.index', [
        'ideas' => $ideas,
    ]);
});

Route::get('/ideas/create', function () {
    return view('ideas.create');
});

Route::post('/ideas/create', function () {
    $title = request('title');
    $description = request('description');
    $state = request('state');
    // Handle form submission logic here
    //session()->push('ideas', $idea);
    $idea = Idea::create([
        'title' => $title,
        'description' => $description,
        'state' => $state,

    ]);
    return redirect('/')->with('success', 'Idea submitted successfully!');
});

Route::get('/ideas/{idea}', function (Idea $idea) {
    //$idea = Idea::findOrFail($id);
    return view('ideas.show', ['idea' => $idea]);
});

Route::get('/ideas/{idea}/edit', function (Idea $idea) {
    // $idea = Idea::findOrFail($id);
    return view('ideas.edit', ['idea' => $idea]);
});

Route::put('/ideas/{idea}', function (Idea $idea) {
    //$idea = Idea::findOrFail($id);
    // $idea->description = request('description');
    // $idea->state = request('state');
    // $idea->save();
    $idea->update([
        'title' => request('title'),
        'description' => request('description'),
        'state' => request('state'),
    ]);
    return redirect("/ideas/{$idea->id}")->with('success', 'Idea updated successfully!');
});


Route::delete('/ideas/{idea}', function (Idea $idea) {
    // $idea = Idea::findOrFail($id);
    $idea->delete();
    return redirect('/')->with('success', 'Idea deleted successfully!');
});