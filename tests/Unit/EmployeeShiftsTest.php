<?php

declare(strict_types=1);

namespace Chad\WhenIWork\Tests\Unit;

use Chad\WhenIWork\EmployeeShifts;
use Chad\WhenIWork\Shift;
use Chad\WhenIWork\WeeklyData;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EmployeeShifts::class)]
class EmployeeShiftsTest extends TestCase
{
    public function testItGetsInvalidShifts(): void
    {
        $subject = new WeeklyData(
            "2021-08-15",
            [
                new Shift(
                    shiftId: 2442377168,
                    employeeId: 36172474,
                    startTime: new DateTimeImmutable("2021-08-19T04:00:00.000000Z"),
                    endTime: new DateTimeImmutable("2021-08-20T04:10:00.000000Z"),
                ),
                new Shift(
                    shiftId: 2442377373,
                    employeeId: 36172474,
                    startTime: new DateTimeImmutable("2021-08-20T04:00:00.000000Z"),
                    endTime: new DateTimeImmutable("2021-08-21T04:00:00.000000Z"),
                ),
                new Shift(
                    shiftId: 2442377373,
                    employeeId: 36172474,
                    startTime: new DateTimeImmutable("2021-08-18T04:00:00.000000Z"),
                    endTime: new DateTimeImmutable("2021-08-18T08:00:00.000000Z"),
                ),
            ]
        );

        $this->assertEqualsCanonicalizing(
            expected: [2442377168, 2442377373],
            actual: $subject->getInvalidShifts(),
        );
    }

    public function testItIgnoresInvalidShiftsInHours(): void
    {
        $subject = new WeeklyData(
            "2021-08-15",
            [
                new Shift(
                    shiftId: 2442377168,
                    employeeId: 36172474,
                    startTime: new DateTimeImmutable("2021-08-19T04:00:00.000000Z"),
                    endTime: new DateTimeImmutable("2021-08-20T04:10:00.000000Z"),
                ),
                new Shift(
                    shiftId: 2442377373,
                    employeeId: 36172474,
                    startTime: new DateTimeImmutable("2021-08-20T04:00:00.000000Z"),
                    endTime: new DateTimeImmutable("2021-08-21T04:00:00.000000Z"),
                ),
                new Shift(
                    shiftId: 2442377374,
                    employeeId: 36172474,
                    startTime: new DateTimeImmutable("2021-08-18T04:00:00.000000Z"),
                    endTime: new DateTimeImmutable("2021-08-18T08:00:00.000000Z"),
                ),
            ]
        );

        $this->assertSame(
            expected: 4.0,
            actual: $subject->getRegularHours(),
        );
    }
}
