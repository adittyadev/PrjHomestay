<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Log request untuk debugging
        Log::info('Register attempt:', $request->all());

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.required' => 'Nomor HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password_confirmation.required' => 'Konfirmasi password harus diisi',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed:', $validator->errors()->toArray());

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);

            // Create tamu
            $tamu = Tamu::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            Log::info('Tamu created successfully', ['tamu_id' => $tamu->id]);

            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'tamu' => $tamu,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Register failed:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
