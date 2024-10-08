<?php 
namespace Strolker\CleanArchitecture\Infrastructure\Persistence\Repositories\Subpoena;

use Strolker\CleanArchitecture\Domain\Repositories\Subpoena\Criteria\SubpoenaCriteria;
use Strolker\CleanArchitecture\Domain\Repositories\Subpoena\SubpoenaRepositoryInterface;
use Strolker\CleanArchitecture\Infrastructure\Persistence\DAO\Subpoena\SubpoenaDAO;

class SubpoenaRepository implements SubpoenaRepositoryInterface
{
    private SubpoenaDAO $dao;

    public function __construct(SubpoenaDAO $dao)
    {
        $this->dao = $dao;
    }

    public function fetchSubpoenas(): array
    {
        $criteria = new SubpoenaCriteria(123, 123);

        return $this->dao->fetchSubpoenas($criteria);
    }
}