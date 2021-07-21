<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\NestoService;

class LoginController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function login(LoginRequest $request) {

        $data = $request->validated();

        if(! auth()->attempt($data)) {
            return back()->with('status', 'Invalid login data.');
        }

        return redirect('/dashboard');
    }

    public function logout() {

        auth()->logout();

    }

}
