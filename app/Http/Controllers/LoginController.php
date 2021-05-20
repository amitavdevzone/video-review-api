<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function handleLogin(Request $request)
    {
        $postData = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $postData['email'])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.wrong_password')],
            ]);
        }

        $token = $user->createToken('web_app')->plainTextToken;

        return response([
            'token' => $token,
            'user_name' => $user->name,
            'role' => $user->role,
        ], 200);
    }
}
