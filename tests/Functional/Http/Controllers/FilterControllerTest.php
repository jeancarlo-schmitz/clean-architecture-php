<?php

namespace Tests\Functional\Http\Controllers;

use Tests\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class FilterControllerTest extends TestCase
{

    public function testApplyFilters()
    {
        // Cria uma requisição POST
        $requestFactory = new ServerRequestFactory();
        $request = $requestFactory->createServerRequest('POST', '/filters/apply')
            ->withParsedBody(['filter_data' => 'example data']);

        // Processa a requisição no aplicativo Slim
        $response = $this->app->handle($request);

        // Adicione asserções para validar a resposta
        $this->assertEquals(200, $response->getStatusCode()); // Ajuste conforme sua lógica
    }
}