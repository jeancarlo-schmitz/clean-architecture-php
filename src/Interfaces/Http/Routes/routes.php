<?php

use Strolker\CleanArchitecture\Application\Controllers\RuleProcessing\ApplyRuleSubpoenaController;
use Psr\Container\ContainerInterface;
use Strolker\CleanArchitecture\Interfaces\Http\Http;

/**
 * Registra todas as rotas da aplicação
 *
 * @param Http $http
 * @param ContainerInterface $container
 */
return function (Http $http, ContainerInterface $container) {
    // Utilize o adaptador para o controlador
    $applyFilterController = $container->get(ApplyRuleSubpoenaController::class);
    $http->route('post', '/filterGroup/{group_id}/apply-rules', $http->adapt($applyFilterController));
};