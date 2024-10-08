<?php

namespace Strolker\CleanArchitecture\Infrastructure\Exception;

use Throwable;
use ErrorException;
use Exception;
use Strolker\CleanArchitecture\Interfaces\Http\Responses\Response;
use Strolker\CleanArchitecture\shared\exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface; // Interface para gerar respostas

class ExceptionHandler
{
    private ResponseFactoryInterface $responseFactory;
    public static array $notices = [];

    public function __construct(ResponseFactoryInterface $responseFactory)
    {

        if ($_ENV['APP_ENV'] === 'prod') {
            // Em produção, esconda notices e deprecated
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
            ini_set('display_errors', '0'); // Não exibir erros
            ini_set('log_errors', '1'); // Logar erros
            ini_set('error_log', '/path/to/your/error.log'); // Log de erros
        } else {
            // Em desenvolvimento, mostre todos os erros
            error_reporting(E_ALL);
            ini_set('display_errors', '1'); // Exibir erros
        }

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            // Verifique se o erro é um notice ou um deprecated
            if ($errno === E_NOTICE || $errno === E_DEPRECATED) {
                self::$notices[] = [
                    'message' => $errstr,
                    'file' => $errfile,
                    'line' => $errline
                ];
            }
        });

        $this->responseFactory = $responseFactory;
    }

    public function handleFatalError()
    {
        $error = error_get_last();
        if ($error !== null && $error['type'] === E_ERROR) {

            // Definindo as variáveis padrão
            $response = $this->responseFactory->createResponse();
            $isDev = $_ENV['APP_ENV'] === 'dev';
            // $statusCode = HttpExceptionConstans::INTERNAL_SERVER_ERROR_CODE; // 500
            $statusCode = 500; // 500
            $message = 'An unexpected fatal error occurred.';
            $errors = [$error]; // Array para os erros

            // Detalhes adicionais para ambiente de desenvolvimento
            $details = [];
            if ($isDev) {
                $details['details'] = [
                    'file' => $error['file'] ?? 'unknown file',
                    'line' => $error['line'] ?? 'unknown line',
                    'trace' => 'No stack trace available for fatal errors.',
                ];
            }
            if (ob_get_length()) {
                ob_end_clean(); 
            }

            Response::error($statusCode, $message, $errors, $details)
                ->create();
        }
    }

    public function handle(ServerRequestInterface $request, Throwable $exception): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();
        $isDev = $_ENV['APP_ENV'] === 'dev';

        $statusCode = 500; // Código de status padrão
        $message = 'An unexpected error occurred.';
        $errors = []; // Array para erros específicos

        if ($exception instanceof ValidationException) {
            // Tratar ValidationException
            $statusCode = $exception->getCode() ?: 400;
            $message = $exception->getMessage();
            $errors = json_decode(json_encode($exception->getValidationErrors()), true);
        } elseif ($exception instanceof ErrorException) {
            $message = 'Internal Server Error';
        } elseif ($exception instanceof Exception) {
            $message = $exception->getMessage();
            $statusCode = $exception->getCode() ?: 500;
            $errors[] = ['message' => $message];
        }

        $details = [];
        if ($isDev) {
            $details['details'] = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        if (ob_get_length() && !$isDev) {
            ob_end_clean(); // Remove a saída do buffer atual
        }
        // Cria a resposta de erro
        $responseBody = Response::error($statusCode, $message, $errors, $details);

        $response->getBody()->write(json_encode($responseBody->getBody()));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode); // Define o status code desejado
    }
}
