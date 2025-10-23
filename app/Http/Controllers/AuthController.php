<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function Register(Request $R): JsonResponse
    {
        try {
            $cred = new User();
            $cred->name = $R->name;
            $cred->email = $R->email;
            $cred->password = Hash::make(value: $R->password);
            $cred->save();
            $response = ['status' => 200, 'message' => 'Register Successfully! Welcome to Our Community'];
            return response()->json(data: $response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e];
        }
    }

    function login(Request $R): JsonResponse
    {
        $user = User::where(column: 'email', operator: $R->email)->first();

        if($user != '[]' && Hash::check(value: $R->password, hashedValue: $user->password)) {
            $token = $user->createToken(name: 'Personal Access Token')->plainTextToken;
            $response = ['status' => 200, 'token' => $token, 'user' => $user, 'message' => 'Successfully Login! Welcome Back'];
            return response()->json(data: $response);
        } else if ($user == '[]') {
            $response = ['status' => 500, 'message' => 'No account found with this email'];
            return response()->json(data: $response);
        } else {
            $response = ['status' => 500, 'message' => 'Wrong email or password! please try again'];
            return response()->json(data: $response);
        }
    }
}
