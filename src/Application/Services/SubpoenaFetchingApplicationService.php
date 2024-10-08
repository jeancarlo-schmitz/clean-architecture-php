<?php

namespace Strolker\CleanArchitecture\Application\Services;

use Strolker\CleanArchitecture\Domain\Repositories\Subpoena\SubpoenaRepositoryInterface;

class SubpoenaFetchingApplicationService{

    private SubpoenaRepositoryInterface $repository;
    public function __construct(SubpoenaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function fetchSubpoenas($parameters): array
    {
        
        return $this->repository->fetchSubpoenas();
    }
    
}