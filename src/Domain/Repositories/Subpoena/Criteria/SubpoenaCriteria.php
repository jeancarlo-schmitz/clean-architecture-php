<?php 

namespace Strolker\CleanArchitecture\Domain\Repositories\Subpoena\Criteria;
class SubpoenaCriteria
{
    private int $lawyerId;
    private int $groupFilterId;
    private ?array $journalList;
    private ?string $dateFrom;
    private ?string $dateTo;

    public function __construct(int $clientId, int $groupFilterId, ?array $journalList = [], ?string $dateFrom = null, ?string $dateTo = null)
    {
        $this->lawyerId = $clientId;
        $this->groupFilterId = $groupFilterId;
        $this->journalList = $journalList;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function getLawyerId(): int
    {
        return $this->lawyerId;
    }

    public function getGroupFilterId(): int
    {
        return $this->groupFilterId;
    }

    public function getJournalList(): array
    {
        return $this->journalList;
    }

    public function hasJournalList(): bool
    {
        return !empty($this->journalList);
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }
}