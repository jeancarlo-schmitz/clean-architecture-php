<?php

namespace Strolker\CleanArchitecture\Domain\Repositories\Subpoena;

use Strolker\CleanArchitecture\Domain\Repositories\Subpoena\Criteria\SubpoenaCriteria;

interface SubpoenaRepositoryInterface{
    public function fetchSubpoenas(): array;
} 