<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RetryMiddleware
{
    private $maxRetries = 3;
    private $retryDelay = 1000; // milisegundos

    public function handle(Request $request, Closure $next)
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            try {
                return $next($request);
            } catch (\Exception $e) {
                $lastException = $e;
                $attempts++;

                if ($attempts < $this->maxRetries) {
                    Log::warning("Intento {$attempts} fallido, reintentando en {$this->retryDelay}ms", [
                        'error' => $e->getMessage(),
                        'route' => $request->path()
                    ]);

                    usleep($this->retryDelay * 1000);
                }
            }
        }

        Log::error("Todos los reintentos fallidos después de {$this->maxRetries} intentos", [
            'error' => $lastException->getMessage(),
            'route' => $request->path()
        ]);

        throw $lastException;
    }
}
