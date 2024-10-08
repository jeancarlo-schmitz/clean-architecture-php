<?php 

namespace Strolker\CleanArchitecture\Infrastructure\Persistence\DAO\Subpoena;

use Strolker\CleanArchitecture\Domain\Repositories\Subpoena\Criteria\SubpoenaCriteria;
use Strolker\CleanArchitecture\Infrastructure\Persistence\DAO\MainDAO;

class SubpoenaDAO extends MainDAO
{
    public function fetchSubpoenas(SubpoenaCriteria $criteria): array
    {
        $sql = "";

        return $this->getArray($sql);
    }
}