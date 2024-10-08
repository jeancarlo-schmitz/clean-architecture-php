<?php

namespace Strolker\CleanArchitecture\Infrastructure\Factories;

use DI\Container;
use Strolker\CleanArchitecture\Application\Controllers\RuleProcessing\ApplyRuleSubpoenaController;
use Strolker\CleanArchitecture\Application\Services\SubpoenaFetchingApplicationService;
use Strolker\CleanArchitecture\Domain\UseCases\RuleProcessing\ApplyRuleSubpoenaUseCase;
use Strolker\CleanArchitecture\Infrastructure\Exception\ExceptionHandler;
use Strolker\CleanArchitecture\Infrastructure\Persistence\DAO\Subpoena\SubpoenaDAO;
use Strolker\CleanArchitecture\Infrastructure\Persistence\Repositories\Subpoena\SubpoenaRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

class SlimContainerFactory
{
    public function create(): ContainerInterface
    {
        $container = new Container();

        $container->set(SubpoenaDAO::class, function (): SubpoenaDAO {
            return new SubpoenaDAO();
        });

        // SubpoenaRepository
        $container->set(SubpoenaRepository::class, function ($container): SubpoenaRepository {
            return new SubpoenaRepository($container->get(SubpoenaDAO::class));
        });

        // SubpoenaFetchingApplicationService
        $container->set(SubpoenaFetchingApplicationService::class, function ($container): SubpoenaFetchingApplicationService {
            return new SubpoenaFetchingApplicationService($container->get(SubpoenaRepository::class));
        });
        
        // Registrar o serviço ApplyFilters
        $container->set(ApplyRuleSubpoenaUseCase::class, function (): ApplyRuleSubpoenaUseCase {
            return new ApplyRuleSubpoenaUseCase();
        });

        // Registrar o controlador FilterController
        $container->set(ApplyRuleSubpoenaController::class, function ($container): ApplyRuleSubpoenaController {
            return new ApplyRuleSubpoenaController(
                $container->get(ApplyRuleSubpoenaUseCase::class), $container->get(SubpoenaFetchingApplicationService::class)
            );
        });

        // Registrar o ResponseFactoryInterface para o ExceptionHandler
        $container->set(ResponseFactoryInterface::class, function (): ResponseFactoryInterface {
            return new ResponseFactory();
        });

        // Registrar o ExceptionHandler
        $container->set(ExceptionHandler::class, function (ContainerInterface $container): ExceptionHandler {
            return new ExceptionHandler(
                $container->get(ResponseFactoryInterface::class)
            );
        });

        return $container;
    }
}
