<?php 

namespace Strolker\CleanArchitecture\Interfaces\Http\Requests\Dtos\RuleProcessing;

use Strolker\CleanArchitecture\Application\Adapters\Validations\ValidationAdapter;
use Strolker\CleanArchitecture\Domain\ValueObjects\Query\QueryIntParam;
use Strolker\CleanArchitecture\shared\exceptions\ValidationException;

class ApplyRuleSubpoenaInputDto {
    private QueryIntParam $groupFilterId;

    public function __construct($groupFilterId)
    {
        $this->groupFilterId = new QueryIntParam($groupFilterId);
        $this->validate();
    }

    public function validate(): void
    {

        $validators = [
            'groupFilterId' => ValidationAdapter::create()->mustBeInt()->notEmpty()
        ];

        $errors = ValidationAdapter::validate($validators, $this);

        if (!empty($errors)) {
            throw new ValidationException($errors); 
        }
    }

    public function getGroupFilterId()
    {
        return $this->groupFilterId->getValue();
    }
}