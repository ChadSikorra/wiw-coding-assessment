<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

use Chad\WhenIWork\Exception\NormalizerException;
use DateTimeImmutable;
use Exception;

class ShiftNormalizer
{
    private const FIELD_SHIFT_ID = 'ShiftID';

    private const  FIELD_EMPLOYEE_ID = 'EmployeeID';

    private const  FIELD_START_TIME = 'StartTime';

    private const  FIELD_END_TIME = 'EndTime';

    /**
     * @param array<string, mixed> $shift
     *
     * @throws NormalizerException
     */
    public function normalize(array $shift): Shift
    {
        $shiftId = $shift[self::FIELD_SHIFT_ID] ?? null;
        $employeeId = $shift[self::FIELD_EMPLOYEE_ID] ?? null;
        $startTime = $shift[self::FIELD_START_TIME] ?? null;
        $endTime = $shift[self::FIELD_END_TIME] ?? null;

        if (!is_int($shiftId)) {
            self::throwInvalidField(self::FIELD_SHIFT_ID);
        }
        if (!is_int($employeeId)) {
            self::throwInvalidField(self::FIELD_EMPLOYEE_ID);
        }
        if (!is_string($startTime)) {
            self::throwInvalidField(self::FIELD_START_TIME);
        }
        if (!is_string($endTime)) {
            self::throwInvalidField(self::FIELD_END_TIME);
        }

        return new Shift(
            shiftId: $shiftId,
            employeeId: $employeeId,
            startTime: self::makeDateTimeFromString($startTime),
            endTime: self::makeDateTimeFromString($endTime),
        );
    }

    /**
     * This could specify / validate the date format further. However, not all data in the given dataset is
     * in RFC3339 format, and it doesn't specifically state how to handle these (ie, ignore or assume some other
     * format).
     *
     * @throws NormalizerException
     */
    private static function makeDateTimeFromString(string $datetime): DateTimeImmutable
    {
        try {
            $datetimeObj = new DateTimeImmutable($datetime);
        } catch (Exception $e) {
            throw new NormalizerException(
                message: sprintf(
                    'Unable to parse datetime string: %s',
                    $datetime,
                ),
                previous: $e,
            );
        }

        return $datetimeObj;
    }

    /**
     * @throws NormalizerException
     */
    private static function throwInvalidField(string $shiftField): never
    {
        throw new NormalizerException(sprintf(
            'The field "%s" is either to present or formatted properly.',
            $shiftField,
        ));
    }
}
