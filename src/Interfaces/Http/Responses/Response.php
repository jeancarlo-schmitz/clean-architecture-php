<?php

namespace Strolker\CleanArchitecture\Interfaces\Http\Responses;

use Exception;
use Strolker\CleanArchitecture\Infrastructure\Exception\ExceptionHandler;
use Strolker\CleanArchitecture\utils\JsonHelper;

class Response
{
    private int $statusCode;
    private mixed $body;
    private string $message;
    private array $errors;
    private array $details;
    private array $headers = [];

    public function __construct(int $statusCode, mixed $body, string $message, array $errors, array $details)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->message = $message;
        $this->errors = $errors;
        $this->details = $details;
    }

    public static function send(int $statusCode, mixed $body, string $message = "", array $errors = [], array $details = []): self
    {
        return new self($statusCode, $body, $message, $errors, $details);
    }
    public static function error(int $statusCode, string $message = "", array $errors = [], array $details = []): self
    {
        return new self($statusCode, null, $message, $errors, $details);
    }

    public function create() {

        $this->headers[] = JsonHelper::getHeader();
        $this->setHeader($this->headers);

        http_response_code($this->statusCode);
        $this->putHeaders();

        ob_end_clean();
        echo JsonHelper::toJsonUtf8($this->getBody());
        exit;
    }

    public function includeError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): array
    {
        $responseBody = [
            'status' => $this->statusCode >= 200 && $this->statusCode < 300 ? 'success' : 'error',
            'statusCode' => $this->statusCode,
            'message' => $this->getMessage(),
            'data' => $this->body,
            'errors' => $this->errors,
        ];

        if(!empty($this->details)){
            $responseBody['details'] = $this->details;
        }

        // Inclui os notices no corpo se estiver em ambiente de desenvolvimento
        if ($_ENV['APP_ENV'] === 'dev') {
            $responseBody['notices'] = ExceptionHandler::$notices; 
        }

        return $responseBody;
    }
    private function getMessage(): string
    {
        // Retorna a mensagem personalizada se estiver definida, caso contrário retorna as mensagens padrão
        if (!empty($this->message)) {
            return $this->message;
        }
    
        if ($this->statusCode >= 200 && $this->statusCode < 300) {
            return "Requisição realizada com sucesso.";
        } elseif ($this->statusCode === 404) {
            return "Recurso não encontrado.";
        } else {
            return "Ocorreu um erro.";
        }
    }

    private function setHeader($value)
    {
        if(!empty($value)) {
            if(is_array($value)){
                $this->headers = array_merge($this->headers, array_values($value));
            }else{
                $this->headers[] = $value;
            }
        }
    }

    private function putHeaders(){
        if(!empty($this->headers)) {
            foreach ($this->headers as $aux) {
                header("$aux");
            }
        }
    }
}
