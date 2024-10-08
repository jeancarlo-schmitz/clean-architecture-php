<?php 

namespace Strolker\CleanArchitecture\Application\Adapters\Validations;

use Exception;
use InvalidArgumentException;

/**
 * @method static RespectValidationAdapter mustBeInt()
 * @method static RespectValidationAdapter notEmpty()
 * @method static RespectValidationAdapter length(int $min, int $max)
 */
class ValidationAdapter
{
    private static ValidationAdapterInterface $adapter;

    // Método para criar o adaptador se necessário utilizar outro adapter, 
    // basta criar a classe no novo adapter, e implementar todos os método do contrato da interface e substituir aqui pelo new OtherValidationAdapter
    public static function create(): ValidationAdapterInterface
    {
        self::$adapter = new RespectValidationAdapter();

        return self::$adapter;
    }

    //Caso alterado a classe de validação, é bom alterar também aqui
    /**
     * @method static RespectValidationAdapter|ValidationAdapterInterface
     */
    public static function validate(array $validators, $dataObject): array
    {
        $errors = [];
    
        foreach ($validators as $field => $validator) {
            $getter = "get" . ucfirst($field);
    
            // Verifica se o método getter existe
            if (!self::hasGetter($dataObject, $getter)) {
                $errors[$field] = "You need to create a getter method for $field.";
                continue;
            }
    
            // Valida o campo e armazena possíveis erros
            $errors[$field] = self::validateField($dataObject, $getter, $validator);
        }
    
        // Remove campos sem erro (ou seja, com valor null)
        return array_filter($errors);
    }
    
    private static function hasGetter($object, string $getter): bool
    {
        return method_exists($object, $getter);
    }
    
    private static function validateField($dataObject, string $getter, $validator): ?array
    {
        try {
            $value = $dataObject->$getter();
            $validator->assert($value);
            return null;
        } catch (Exception $exception) {
            return $validator->getErrors();
        }
    }
}