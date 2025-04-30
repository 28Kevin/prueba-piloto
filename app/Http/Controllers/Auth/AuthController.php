<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    private $loginTimeout = 30; // segundos
    private $maxAttempts = 3;

    public function login(LoginRequest $request)
    {
        try {
            $ip = $request->ip();
            $cacheKey = "login_attempts_{$ip}";
            
            // Verificar intentos de login
            $attempts = Cache::get($cacheKey, 0);
            if ($attempts >= $this->maxAttempts) {
                return response()->json([
                    'errors' => [[
                        'status' => '429',
                        'title' => 'Too Many Attempts',
                        'detail' => 'Demasiados intentos de inicio de sesión. Por favor, intente nuevamente en ' . $this->loginTimeout . ' segundos.'
                    ]]
                ], 429);
            }

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                // Incrementar intentos fallidos
                Cache::put($cacheKey, $attempts + 1, $this->loginTimeout);
                
                return response()->json([
                    'errors' => [[
                        'status' => '401',
                        'title' => 'Unauthorized',
                        'detail' => 'Usuario o contraseña incorrectos',
                        'attempts' => $attempts + 1,
                        'remaining_attempts' => $this->maxAttempts - ($attempts + 1)
                    ]]
                ], 401);
            }

            // Resetear intentos en login exitoso
            Cache::forget($cacheKey);

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
                'trace' => $e->getTraceAsString()
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
