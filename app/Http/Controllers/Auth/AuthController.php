<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'errors' => [[
                        'status' => '401',
                        'title' => 'Unauthorized',
                        'detail' => 'Usuario o contraseña incorrectos'
                    ]]
                ], 401);
            }

            $user = Auth::user();
            $accessToken = $user ? $user->createToken('PassportAuth') : null;

            $response = [
                'links' => [
                    'self' => URL::current(),
                ],
                'data' => [
                    'type' => 'tokens',
                    'id' => (string) $user->id,
                    'attributes' => [
                        'access_token' => $accessToken->accessToken,
                        'expires_at' => Carbon::parse($accessToken->token->expires_at)->toDateTimeString(),
                        'message' => 'Bienvenido',
                    ],
                    'relationships' => [
                        'user' => [
                            'links' => [],
                            'data' => [
                                'type' => 'users',
                                'id' => (string) $user->id,
                            ],
                        ],
                    ],
                    'links' => [
                        'self' => URL::current(),
                    ],
                ],
                'included' => [
                    [
                        'type' => 'users',
                        'id' => (string) $user->id,
                        'attributes' => [
                            'name' => $user->name,
                            'email' => $user->email,
                        ],
                        'links' => [
                            'self' => url("/api/users/{$user->id}"),
                        ],
                    ],
                ],
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Server Error',
                    'detail' => 'Ocurrió un error al intentar iniciar sesión'
                ]]
            ], 500);
        }
    }
}
