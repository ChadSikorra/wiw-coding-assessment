<?php

declare(strict_types=1);

namespace Chad\WhenIWork\Tests\Unit;

use Chad\WhenIWork\Shift;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Shift::class)]
class ShiftTest extends TestCase
{
    public function testItGetsTheStartOfTheWeekInTheExpectedFormat(): void
    {
        $subject = new Shift(
            shiftId: 2635475284,
            employeeId: 40720167,
            startTime: new DateTimeImmutable("2021-08-26T00:00:00.000000Z"),
            endTime: new DateTimeImmutable("2021-08-26T12:30:00.000000Z"),
        );

        $this->assertSame(
            "2021-08-22",
            $subject->getStartOfWeekAsString(),
        );
    }

    public function testItGetsTheStartOfTheWeekInTheExpectedFormatWhenWeStartOnSunday(): void
    {
        $subject = new Shift(
            shiftId: 2635475284,
            employeeId: 40720167,
            startTime: new DateTimeImmutable("2021-08-22T00:00:00.000000Z"),
            endTime: new DateTimeImmutable("2021-08-22T12:30:00.000000Z"),
        );

        $this->assertSame(
            "2021-08-22",
            $subject->getStartOfWeekAsString(),
        );
    }

    public function testItGetsTheExpectedTotalHours(): void
    {
        $subject = new Shift(
            shiftId: 2635475284,
            employeeId: 40720167,
            startTime: new DateTimeImmutable("2021-08-26T00:00:00.000000Z"),
            endTime: new DateTimeImmutable("2021-08-26T12:30:00.000000Z"),
        );

        $this->assertSame(
            12.50,
            $subject->getTotalHours(),
        );
    }

    public function testOverlapsWithOneWhenItDoes(): void
    {
        $subject = new Shift(
            shiftId: 2442377373,
            employeeId: 36172474,
            startTime: new DateTimeImmutable("2021-08-20T04:00:00.000000Z"),
            endTime: new DateTimeImmutable("2021-08-21T04:00:00.000000Z"),
        );

        $overlap = new Shift(
            shiftId: 2442377168,
            employeeId: 36172474,
            startTime: new DateTimeImmutable("2021-08-19T04:00:00.000000Z"),
            endTime: new DateTimeImmutable("2021-08-20T04:10:00.000000Z"),
        );

        $this->assertTrue($subject->overlapsWithOne($overlap));
    }

    public function testOverlapsWithOneWhenItDoesNot(): void
    {
        $subject = new Shift(
            shiftId: 2442377373,
            employeeId: 36172474,
            startTime: new DateTimeImmutable("2021-08-20T04:00:00.000000Z"),
            endTime: new DateTimeImmutable("2021-08-21T04:00:00.000000Z"),
        );

        $overlap = new Shift(
            shiftId: 2442377168,
            employeeId: 36172474,
            startTime: new DateTimeImmutable("2021-08-19T04:00:00.000000Z"),
            endTime: new DateTimeImmutable("2021-08-20T04:00:00.000000Z"),
        );

        $this->assertFalse($subject->overlapsWithOne($overlap));
    }
}
