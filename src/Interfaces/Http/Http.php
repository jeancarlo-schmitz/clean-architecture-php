<?php 
namespace Strolker\CleanArchitecture\Interfaces\Http;

use Strolker\CleanArchitecture\Application\Controllers\Interfaces\RequestControllerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

interface Http
{
    public function route(string $method, string $url, callable $callback);
    public function run();

    /**
     * Adapta um controlador específico para ser usado como um callable.
     *
     * @param mixed $controller O controlador a ser adaptado.
     * @return callable O callable adaptado.
     */
    public function adapt(RequestControllerInterface $controller): callable;
    public function handleError(ServerRequestInterface $request, Throwable $exception): ResponseInterface;
    public function getContainerFactory(): ContainerInterface;
}