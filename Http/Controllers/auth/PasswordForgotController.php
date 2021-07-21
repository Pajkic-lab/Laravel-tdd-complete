<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class PasswordForgotController extends Controller
{
    public function index() {
        return view('pages.forgotPasswordReset');
    }

    public function show() {
        return view('pages.forgotPassword');
    }

    public function forgot(ForgotPasswordRequest $request) {

        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if($user) {

            $key = config('app.JWT_SECRET');
            $payload = array(
                "id" => $user['id'],
                "exp" => Carbon::now()->addMinutes(15)->timestamp,
            );
            $jwt = JWT::encode($payload, $key);

            $email = $user['email'];
            $username = strstr($email, '@', true);

            Mail::to($data['email'])->send(new ForgotPasswordMail($jwt, $username));

            return redirect('/login');
        }
        return response()->json(['message' => 'error'], 400);
    }

    public function checkJwt(Request $request, $jwt) {

        $key = config('app.JWT_SECRET');
        $token = JWT::decode($jwt, $key, array('HS256'));
        $user = User::find($token->id);

        if($user) {
            return view('pages.forgotPasswordReset', ['jwt' => $jwt]);
        } else {
            return redirect('/login');
        }
    }

    public function reset(PasswordResetRequest $request, $jwt) {

        $data = $request->validated();

        $key = config('app.JWT_SECRET');
        $token = JWT::decode($jwt, $key, array('HS256'));
        $user = User::find($token->id);

        if($user) {

        $user->password = Hash::make($data['password']);

        $user->save();

        return redirect('/login');

        }

        return response()->json(['message' => 'error'], 400);

    }

}
