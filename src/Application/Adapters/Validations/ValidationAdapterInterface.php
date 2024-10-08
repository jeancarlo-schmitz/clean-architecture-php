<?php 
namespace Strolker\CleanArchitecture\Application\Adapters\Validations;

interface ValidationAdapterInterface
{
    // Define que o valor deve ser um inteiro
    public function mustBeInt(): self;

    // Define que o valor não deve ser vazio
    public function notEmpty(): self;

    // Define o comprimento mínimo e máximo do valor
    public function length($min, $max): self;

    // Define que o valor deve ser um email válido
    public function email(): self;

    // Define que o valor deve ser uma URL válida
    public function url(): self;

    // Define uma expressão regular para validar o valor
    public function regex(string $pattern): self;

    // Define que o valor deve ser uma data válida
    public function date(): self;

    // Tenta validar o valor e lança uma exceção em caso de falha
    public function assert($value): void;

    // Permite acessar o validador subjacente para casos específicos
    public function getValidator();
    public function getErrors():array;
}