<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

class WeeklyData
{
    private float $totalHours = 0;

    /**
     * @param string $startOfWeek
     * @param Shift[] $shifts
     */
    public function __construct(
        private readonly string $startOfWeek,
        private readonly array $shifts,
    ) {
        foreach ($this->shifts as $shift) {
            $this->totalHours += $shift->getTotalHours();
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
        $invalid = [];

        foreach ($this->shifts as $shift) {
            if ($shift->overlapsWithAny(...$this->shifts)) {
                $invalid[] = $shift->getShiftId();
            }
        }

        return $invalid;
    }
}
