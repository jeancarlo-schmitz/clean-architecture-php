<?php

namespace Strolker\CleanArchitecture\Application\Controllers\RuleProcessing;

use Strolker\CleanArchitecture\Application\Controllers\Interfaces\RequestControllerInterface;
use Strolker\CleanArchitecture\Application\Services\SubpoenaFetchingApplicationService;
use Strolker\CleanArchitecture\Domain\UseCases\RuleProcessing\ApplyRuleSubpoenaUseCase;
use Strolker\CleanArchitecture\Interfaces\Http\Requests\Dtos\RuleProcessing\ApplyRuleSubpoenaInputDto;
use Strolker\CleanArchitecture\Interfaces\Http\Requests\Request;
use Strolker\CleanArchitecture\Interfaces\Http\Responses\Response;

class ApplyRuleSubpoenaController implements RequestControllerInterface
{
    private ApplyRuleSubpoenaUseCase $applyRuleSubpoenaUseCase;
    private SubpoenaFetchingApplicationService $subpoenaFetchingApplicationService;

    public function __construct(ApplyRuleSubpoenaUseCase $applyRuleSubpoenaUseCase, SubpoenaFetchingApplicationService $subpoenaFetchingApplicationService)
    {
        $this->applyRuleSubpoenaUseCase = $applyRuleSubpoenaUseCase;
        $this->subpoenaFetchingApplicationService = $subpoenaFetchingApplicationService;
    }

    public function execute(Request $request): Response{
        $filters = $request->getParameters() ?? [];
        $applyRuleSubpoenaData = new ApplyRuleSubpoenaInputDto($filters['group_id']);
        $subpoenaDto = $this->subpoenaFetchingApplicationService->fetchSubpoenas($filters);
        // $result = $this->applyRuleSubpoenaUseCase->execute($filters);
        
        return Response::send(200, $applyRuleSubpoenaData);
    }
}