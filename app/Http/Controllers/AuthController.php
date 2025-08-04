<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'User registration failed'
            ], 500);
        }

        try {
            $token = auth('api')->login($user);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Could not create token'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => $user,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid credentials'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Could not create token'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => auth('api')->user(),
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ]
        ]);
    }

    public function logout()
    {
        try {
            auth('api')->logout();
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to logout, please try again'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function getUser()
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'User not found'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch user profile'
            ], 500);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . auth('api')->id()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }

        return response()->json([
            'debug' => [
                'request_all' => $request->all(),
                'request_input' => $request->input(),
                'content_type' => $request->header('Content-Type'),
                'raw_content' => $request->getContent(),
                'user_id' => auth('api')->id()
            ]
        ]);

        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'User not found'
                ], 404);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            
            if (!$user->save())
            {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to update user'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }
}
