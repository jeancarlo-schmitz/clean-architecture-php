<?php 

namespace Strolker\CleanArchitecture\Application\Controllers\Interfaces;

use Strolker\CleanArchitecture\Interfaces\Http\Requests\Request;
use Strolker\CleanArchitecture\Interfaces\Http\Responses\Response;

interface RequestControllerInterface
{
    public function execute(Request $request): Response;
}