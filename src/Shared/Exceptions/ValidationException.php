<?php 
namespace Strolker\CleanArchitecture\Shared\exceptions;

use Exception;
use Strolker\CleanArchitecture\Domain\ValueObjects\StringFormatters\ErrorListFormatter;

class ValidationException extends Exception
{
    protected array $errors;

    public function __construct( array $errors = [], string $message = "Validation Data Error", int $code = 400)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getValidationErrors(): array
    {
        return $this->errors;
    }
}