<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request) {
        try {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required']
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)) {
            return $this->sendError('Unautorhized', 'Authentication Failed!', 500);
        }

        $user = User::where('email', $request->email)->first();

        if(!Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Invalid Credentials!');
        }

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return $this->sendResponse([
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'user' => $user
        ], 'Authenticated');
        } catch (Exception $error) {
            return $this->sendError([
                'message' => 'Terjadi Kesalahan!',
                'error' => $error
            ],
            'Login Gagal');
        }
    }

    public function store(Request $request) {
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
                'password' => ['required', 'min:6']
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            // $tokenResult = $user->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ];

            return $this->sendResponse($data, 'Registrasi Berhasil!');
        
        }catch (Exception $error) {
            return $this->sendError([
                'message' => 'Terjadi Kesalahan!',
                'error' => $error
            ],
            'Registrasi Gagal');
        }
    }

    public function show(User $user) {
        try{
            $user = Auth::user($user);

            return $this->sendResponse($user, 'Sukses mendapatkan data!');
        } catch (Exception $error) {
            return $this->sendError([
                'message' => 'Terjadi Kesalahan!',
                'error' => $error
            ], 'User gagal didapatkan!');
        }
    }

    public function logout() {
        $user = User::find(Auth::user()->id);

        $user->tokens()->delete();

        return response()->noContent();
    }
}
