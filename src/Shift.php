<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

use DateTimeImmutable;

final readonly class Shift
{
    public function __construct(
        private int $shiftId,
        private int $employeeId,
        private DateTimeImmutable $startTime,
        private DateTimeImmutable $endTime,
    ) {
    }

    public function getShiftId(): int
    {
        return $this->shiftId;
    }

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getEndTime(): DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * Based on the wording, I think it meant Sunday at 00:00 in the PDF. Though it could mean the start of Monday as
     * well? Would need clarification.
     */
    public function getStartOfWeekAsString(): string
    {
        // If we are already on a Sunday, we need to just return the current date.
        if ($this->getStartTime()->format('w') === '0') {
            return $this
                ->getStartTime()
                ->format('Y-m-d');
        }

        return $this
            ->getStartTime()
            ->modify('last Sunday')
            ->format('Y-m-d');
    }

    public function getTotalHours(): float
    {
        $interval = $this
            ->getStartTime()
            ->diff($this->getEndTime());

        return round(
            num: $interval->h + ($interval->i / 60),
            precision: 2,
        );
    }

    public function overlapsWithAny(Shift ...$shifts): bool
    {
        foreach ($shifts as $shift) {
            if ($this->overlapsWithOne($shift)) {
                return true;
            }
        }

        return false;
    }

    public function overlapsWithOne(Shift $shift): bool
    {
        // Avoid an accidental comparison with the same instance.
        // Reduces some logic around filtering when doing overlaps.
        if ($shift === $this) {
            return false;
        }

        // This assumes we let shifts go side-by-side.
        // A shift starting and ending at the same time might not be good, though the instructions don't say.
        return $shift->getEndTime() > $this->getStartTime()
            && $shift->getStartTime() < $this->getEndTime();
    }
}
