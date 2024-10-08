<?php

namespace Strolker\CleanArchitecture\Interfaces\Http\Requests;

class Request
{
    private array $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
