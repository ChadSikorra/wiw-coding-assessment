<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

class WeeklyData
{
    private float $totalHours = 0.0;

    /**
     * @var int[]|null
     */
    private ?array $invalidShifts = null;

    /**
     * @param string $startOfWeek
     * @param Shift[] $shifts
     */
    public function __construct(
        private readonly string $startOfWeek,
        private readonly array $shifts,
    ) {
        foreach ($this->shifts as $shift) {
            if ($this->isShiftValid($shift->getShiftId())) {
                $this->totalHours += $shift->getTotalHours();
            }
        }
    }

    public function getOvertimeHours(): float
    {
        if ($this->totalHours <= 40.00) {
            return 0.00;
        }

        return $this->totalHours - 40.00;
    }

    public function getStartOfWeek(): string
    {
        return $this->startOfWeek;
    }

    public function getRegularHours(): float
    {
        if ($this->totalHours >= 40.00) {
            return 40.00;
        }

        return $this->totalHours;
    }

    /**
     * @return int[]
     */
    public function getInvalidShifts(): array
    {
        if ($this->invalidShifts !== null) {
            return $this->invalidShifts;
        }
        $this->invalidShifts = [];

        foreach ($this->shifts as $shift) {
            if ($shift->overlapsWithAny(...$this->shifts)) {
                $this->invalidShifts[] = $shift->getShiftId();
            }
        }

        return $this->invalidShifts;
    }

    public function isShiftValid(int $shiftId): bool
    {
        return !in_array(
            needle: $shiftId,
            haystack: $this->getInvalidShifts(),
            strict: true,
        );
    }
}
