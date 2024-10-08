<?php 
namespace Strolker\CleanArchitecture\Shared\exceptions;
class InvalidJsonException extends \InvalidArgumentException
{
    public function __construct($message = "JSON inválido", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}