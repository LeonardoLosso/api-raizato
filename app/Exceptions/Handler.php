<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        switch (get_class($exception)) {
            // trata erro não autenticado
            case AuthenticationException::class:
                return response()->json([
                    'message' => 'Não autenticado.',
                ], 401);

            case AuthorizationException::class:
                return response()->json([
                    'message' => 'Ação não autorizada.',
                ], 403);

            case QueryException::class:
                // trata erro de conexão com o banco
                if (str_contains($exception->getMessage(), '[2002]')) {
                    return response()->json([
                        'message' => 'Não foi possível conectar ao banco de dados. Por favor, verifique a conexão.',
                        'error' => 'Database Connection Error'
                    ], 500);
                }
                break;

            default:
                return parent::render($request, $exception);
        }

        return response()->json([
            'message' => 'Um erro inesperado ocorreu.',
        ], 500);
    }
}
