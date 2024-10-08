<?php

namespace Strolker\CleanArchitecture\Application\Adapters\Http;

use Strolker\CleanArchitecture\Application\Controllers\Interfaces\RequestControllerInterface;
use Strolker\CleanArchitecture\Infrastructure\Exception\ExceptionHandler;
use Strolker\CleanArchitecture\Infrastructure\Factories\SlimContainerFactory;
use Strolker\CleanArchitecture\Interfaces\Http\Http;
use Strolker\CleanArchitecture\Middleware\RemoveLocalServerPathMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Factory\AppFactory;
use Slim\App;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Strolker\CleanArchitecture\Interfaces\Http\Requests\Request as MyRequest;
use Strolker\CleanArchitecture\Shared\Exceptions\InvalidJsonException;

class SlimHttpAdapter implements Http
{
    private App $app;
    private $exceptionHandler;
    private ContainerInterface $container;

    public function __construct()
    {
        $this->app = AppFactory::create();
        $this->container = (new SlimContainerFactory())->create();
        AppFactory::setContainer($this->container);

        $this->app = AppFactory::create();

        $this->app->add(new RemoveLocalServerPathMiddleware());

        $responseFactory = $this->container->get(ResponseFactoryInterface::class);
        $this->exceptionHandler = new ExceptionHandler($responseFactory);
        $this->app->addBodyParsingMiddleware();

        register_shutdown_function([$this->exceptionHandler, 'handleFatalError']);

        $errorMiddleware = $this->app->addErrorMiddleware(true, false, false);
        $errorMiddleware->setDefaultErrorHandler([$this, 'handleError']);
    }

    public function route(string $method, string $url, callable $callback)
    {
        $method = strtolower($method);
        $this->app->$method($url, function (ServerRequestInterface $request, ResponseInterface $response, array $args) use ($callback): ResponseInterface {
            $parameters = self::getRequestParameter($request, $args);

            return $callback($parameters, $response);
        });
    }

    public static function getRequestParameter(ServerRequestInterface $request, array $args): array
    {
        // Agrupando todos os parâmetros em um único array
        $bodyContent = (string) $request->getBody();
        $decodedBody = json_decode($bodyContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException('JSON inválido: ' . json_last_error_msg());
        }

        $parameters = array_merge(
            $request->getQueryParams(),      // Parâmetros de query string (GET)
            $decodedBody ?? [],              // Parâmetros de corpo (POST, PUT, etc.)
            $request->getParsedBody() ?? [],  // Parâmetros do corpo da requisição já processados
            $args
        );

        return $parameters;
    }

    /**
     * @param mixed $controller
     * @return callable
     */
    public function adapt(RequestControllerInterface $controller): callable
    {
        return function (array $parametros, ResponseInterface $response) use ($controller): ResponseInterface {
            $controllerResponse = $controller->execute(new MyRequest($parametros));

            $isDev = $_ENV['APP_ENV'] === 'dev';

            if (ob_get_length() && !$isDev) {
                ob_end_clean(); // Remove a saída do buffer atual
            }

            $response->getBody()->write(json_encode($controllerResponse->getBody()));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($controllerResponse->getStatusCode());
        };
    }

    public function run()
    {
        $this->app->run();
    }

    public function handleError(ServerRequestInterface $request, Throwable $exception): ResponseInterface
    {
        return $this->exceptionHandler->handle($request, $exception);
    }

    public function getContainerFactory(): ContainerInterface
    {
        return $this->container;
    }
}
