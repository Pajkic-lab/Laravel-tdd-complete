<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PassResetBehindLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function index() {
        return view('pages.passwordReset');
    }

    public function reset(PassResetBehindLoginRequest $request) {

        $data = $request->validated();

        if(Hash::check( $data['oldPassword'], auth()->user()->password )) {

            $user = User::find(auth()->user()->id);

            $user->password = Hash::make($data['password']);

            $user->save();

            return redirect('/dashboard');
        };
        return response()->json(['message' => 'error'], 400);
    }
}
