<?php 
namespace Strolker\CleanArchitecture\Application\Adapters\Validations;

use Exception;
use Respect\Validation\Validator as RespectValidator;
use Respect\Validation\Exceptions\NestedValidationException;

class RespectValidationAdapter implements ValidationAdapterInterface
{
    private $validator;
    private array $errors = [];

    public function __construct()
    {
        $this->validator = RespectValidator::create(); // Inicializa um validador vazio
    }

    public function mustBeInt(): self
    {
        $this->validator = $this->validator->intType();
        return $this;
    }

    public function notEmpty(): self
    {
        $this->validator = $this->validator->notEmpty();
        return $this;
    }

    public function length($min, $max): self
    {
        $this->validator = $this->validator->length($min, $max);
        return $this;
    }

    public function email(): self
    {
        $this->validator = $this->validator->email();
        return $this;
    }

    public function url(): self
    {
        $this->validator = $this->validator->url();
        return $this;
    }

    public function regex(string $pattern): self
    {
        $this->validator = $this->validator->regex($pattern);
        return $this;
    }

    public function date(): self
    {
        $this->validator = $this->validator->date();
        return $this;
    }

    public function assert($value): void
    {
        try {
            $this->validator->assert($value);
        } catch (NestedValidationException $exception) {
            $this->errors = $exception->getMessages();
            throw new Exception("Validation Fail");
        }
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function getErrors(): array{
        return $this->errors;
    }
}