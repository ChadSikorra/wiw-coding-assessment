<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

class EmployeeShifts
{
    /**
     * @var null|WeeklyData[]
     */
    private ?array $weeklyShifts = null;

    /**
     * @param Shift[] $shifts
     */
    public function __construct(
        private readonly int $employeeId,
        private readonly array $shifts,
    ) {
    }

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    /**
     * @return WeeklyData[]
     */
    public function getWeeklyData(): array
    {
        if ($this->weeklyShifts !== null) {
            return $this->weeklyShifts;
        }
        $weeklyShifts = [];

        foreach ($this->shifts as $shift) {
            $weeklyShifts[$shift->getStartOfWeekAsString()][] = $shift;
        }

        $this->weeklyShifts = array_map(
            fn(string $startOfWeek) => new WeeklyData(
                $startOfWeek,
                $weeklyShifts[$startOfWeek],
            ),
            array_keys($weeklyShifts)
        );

        return $this->weeklyShifts;
    }
}
