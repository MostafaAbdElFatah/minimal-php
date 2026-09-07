<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(StoreRegisterRequest $request)
    {
        $user = User::create($request->validated());

        Auth::login($user);

        return redirect('/')->with('status', 'Account created successfully!');
    }
}
