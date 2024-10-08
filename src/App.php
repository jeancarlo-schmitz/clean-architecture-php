<?php

namespace Strolker\CleanArchitecture;

use Strolker\CleanArchitecture\Interfaces\Http\Http;
use Psr\Container\ContainerInterface;

class App
{
    private Http $http;
    private ContainerInterface $container;

    public function __construct(Http $http)
    {
        $this->http = $http;
        $this->container = $http->getContainerFactory();

        // Carrega as rotas
        $this->loadRoutes();
    }

    private function loadRoutes()
    {
        // Carrega as rotas do arquivo de rotas
        (require __DIR__ . '/Interfaces/Http/Routes/routes.php')($this->http, $this->container);
    }

    public function run()
    {
        $this->http->run();
    }
}