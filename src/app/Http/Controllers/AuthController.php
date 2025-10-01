<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;
use App\Http\Requests\Auth\RegisterRequest as AuthRegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;


class AuthController extends Controller
{

    public function register(AuthRegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'cpf'      => $request->cpf,
            'phone'    => $request->phone,
        ]);

        return response()->json($user, 201);
    }

    public function login(AuthLoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['As credenciais são incorretas.']
                ]);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                // 'user'  => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'errors' => [
                        'email' => [$e->getMessage()]
                    ]
                ], 403)
            );
        }

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Deslogado com sucesso']);
    }
}
