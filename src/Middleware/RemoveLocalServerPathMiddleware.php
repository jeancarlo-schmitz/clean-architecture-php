<?php

namespace Strolker\CleanArchitecture\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class RemoveLocalServerPathMiddleware
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Obtém a URI da requisição
        $uri = $request->getUri()->getPath();
        
        // Remove o caminho do servidor local da URI
        $uri = $this->removeLocalServerPath($uri);
        
        // Cria uma nova requisição com a URI modificada
        $request = $request->withUri($request->getUri()->withPath($uri));

        // Passa a requisição adiante
        return $handler->handle($request);
    }

    private function removeLocalServerPath($uri)
    {
        $documentRoot = str_replace("/", "\\", __DIR__);
        $documentRoot = explode("\\", $documentRoot);

        foreach ($documentRoot as $path) {
            if (empty($path)) {
                continue;
            }
            $uri = str_replace("/" . $path, "", $uri);
        }

        return $uri;
    }
}