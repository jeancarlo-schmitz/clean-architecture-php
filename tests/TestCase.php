<?php

namespace Tests;

use Slim\Factory\AppFactory;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Strolker\CleanArchitecture\Infrastructure\Factories\ContainerFactory;

class TestCase extends BaseTestCase
{
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();
        
        $container = (new ContainerFactory())->create();
        AppFactory::setContainer($container);
        
        // Configura o aplicativo Slim
        $this->app = AppFactory::create();
        // Carregue suas rotas aqui
        (require __DIR__ . '/../src/Interfaces/Http/Routes/routes.php')($this->app);
    }
}