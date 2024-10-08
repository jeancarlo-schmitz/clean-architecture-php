<?php 
namespace Strolker\CleanArchitecture\Domain\ValueObjects\Query;

class QueryIntParam
{
    private $originalValue;
    private $intValue;

    public function __construct($value)
    {
        $this->originalValue = $value;
        $this->intValue = $this->castToInt($value);
    }

    // Método que tenta fazer o cast para int
    private function castToInt($value): ?int
    {
        if (is_numeric($value) && (int)$value == $value) {
            return (int)$value;
        }
        return null;
    }

    // Retorna o valor castado, se válido, ou o valor original
    public function getValue()
    {
        return $this->intValue !== null ? $this->intValue : $this->originalValue;
    }

    // Verifica se o valor é um int válido
    public function isValid(): bool
    {
        return $this->intValue !== null;
    }
}