<?php

namespace App\Exceptions;

use App\Traits\HttpResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

use function Laravel\Prompts\error;

class Handler extends ExceptionHandler
{
    use HttpResponses;

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
            case AuthenticationException::class:
                return $this->error('Não autenticado.', 401);

            case AuthorizationException::class:
                return $this->error(
                    'Ação não autorizada',
                    403,
                    [
                        $exception->getMessage(),
                        'LINE: ' . $exception->getLine(),
                        'FILE: ' . $exception->getFile(),
                        $exception->__toString()
                    ]
                );

            case NotFoundHttpException::class:
                return $this->error(
                    'Recurso não encontrado.',
                    404,
                    [
                        $exception->getMessage(),
                        'LINE: ' . $exception->getLine(),
                        'FILE: ' . $exception->getFile()
                    ]
                );

            case QueryException::class:
                if (str_contains($exception->getMessage(), '[2002]')) {

                    return $this->error(
                        'Não foi possível conectar ao banco de dados. Por favor, verifique a conexão.',
                        500,
                        [
                            $exception->getMessage(),
                            'LINE: ' . $exception->getLine(),
                            'FILE: ' . $exception->getFile()
                        ]
                    );
                }
                break;

            default:
                return $this->error(
                    $exception->getMessage(),
                    $exception->getCode(),
                    [
                        'LINE: ' . $exception->getLine(),
                        'FILE: ' . $exception->getFile()
                    ],
                    [$request->__toString()]
                );
                // return parent::render($request, $exception);
        }

        return $this->error(
            'Um erro inesperado ocorreu.',
            500,
            [
                $exception->getMessage(),
                'LINE: ' . $exception->getLine(),
                'FILE: ' . $exception->getFile()
            ]
        );
    }
}
