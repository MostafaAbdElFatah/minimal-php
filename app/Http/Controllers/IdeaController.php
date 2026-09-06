<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdeaRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Idea;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        // session()->get('ideas', []);
        // $ideas = DB::table('ideas')->get();
        // $ideas = Idea::all()

        $ideas = Idea::query()
            ->when(request('state'), function ($query, $state) {
                $query->where('state', $state);
            })
            ->get();

        return view('ideas.index', [
            'ideas' => $ideas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ideas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaRequest $request) {
        // $request = request();
        // $request->merge([
        //     'title' => trim($request->input('title', '')),
        //     'description' => trim($request->input('description', '')),
        //     'state' => trim($request->input('state', '')),
        // ]);
        // request()->validate([
        //     // old 'required|string|max:255',
        //     'title' => [
        //         'required',
        //         'string',
        //         'min:3',
        //         'max:255',
        //     ],
        //     'description' => [
        //         'required',
        //         'string',
        //         'min:10',
        //     ],
        //     'state' => [
        //         'required',
        //         'in:'.
        //             implode(',', array_map(fn ($state) => $state->value, IdeaState::cases())),
        //     ],
        // ]);
        $title = request('title');
        $description = request('description');
        $state = request('state');
        // Handle form submission logic here
        // session()->push('ideas', $idea);
        $idea = Idea::create([
            'title' => $title,
            'description' => $description,
            'state' => $state,
        ]);

        return redirect('/')->with('status', 'Idea submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea) {
        // $idea = Idea::findOrFail($id);
        return view('ideas.show', ['idea' => $idea]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea) {
        // $idea = Idea::findOrFail($id);
        return view('ideas.edit', ['idea' => $idea]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaRequest $request, Idea $idea) {
        // $request = request();
        // $request->merge([
        //     'title' => trim($request->input('title', '')),
        //     'description' => trim($request->input('description', '')),
        //     'state' => trim($request->input('state', '')),
        // ]);
        // request()->validate([
        //     // old 'required|string|max:255',
        //     'title' => [
        //         'required',
        //         'string',
        //         'min:3',
        //         'max:255',
        //     ],
        //     'description' => [
        //         'required',
        //         'string',
        //         'min:10',
        //     ],
        //     'state' => [
        //         'required',
        //         'in:'.
        //             implode(',', array_map(fn ($state) => $state->value, IdeaState::cases())),
        //     ],
        // ]);
        // $idea = Idea::findOrFail($id);
        // $idea->description = request('description');
        // $idea->state = request('state');
        // $idea->save();
        $idea->update([
            'title' => request('title'),
            'description' => request('description'),
            'state' => request('state'),
        ]);

        return redirect("/ideas/{$idea->id}")->with('status', 'Idea updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea) {
        // $idea = Idea::findOrFail($id);
        $idea->delete();

        return redirect('/')->with('status', 'Idea deleted successfully!');
    }

    /**
     * Remove all ideas from storage.
     */
    public function destroyAll(): RedirectResponse {
        Idea::query()->delete();

        return redirect('/')->with('status', 'All ideas deleted successfully!');
    }
}
