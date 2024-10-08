<?php

use Strolker\CleanArchitecture\App;
use Strolker\CleanArchitecture\Application\Adapters\Http\SlimHttpAdapter;

require __DIR__ . '/vendor/autoload.php';

// Definir APP_ENV com base no host
if (!isset($_ENV['APP_ENV'])) {
    if (isset($_SERVER['HTTP_HOST']) && (
        $_SERVER['HTTP_HOST'] === 'localhost' ||
        $_SERVER['HTTP_HOST'] === 'localhost:8000' ||
        $_SERVER['HTTP_HOST'] === 'localhost:8082' ||
        $_SERVER['HTTP_HOST'] === 'localhost:8083' ||
        $_SERVER['HTTP_HOST'] === 'localhost:8081'
    )) {
        $_ENV['APP_ENV'] = 'dev'; // Define como dev
    } else {
        $_ENV['APP_ENV'] = 'prod'; // Define como prod
    }
}

// Carregar o arquivo .env apropriado
$envFile = __DIR__ . '/src/Infrastructure/Database/.env.' . $_ENV['APP_ENV'];
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/src/Infrastructure/Database/', '.env.' . $_ENV['APP_ENV']);
    $dotenv->load();
} else {
    throw new Exception("Arquivo de ambiente não encontrado: " . $envFile);
}

// Criar a aplicação
$http = new SlimHttpAdapter();
$app = new App($http);
$app->run();