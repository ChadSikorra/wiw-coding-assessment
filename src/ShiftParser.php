<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

use Chad\WhenIWork\Exception\NormalizerException;
use Chad\WhenIWork\Exception\ParseException;

class ShiftParser
{
    public function __construct(private readonly ShiftNormalizer $shiftNormalizer = new ShiftNormalizer())
    {
    }

    /**
     * @return EmployeeShifts[]
     *
     * @throws ParseException
     */
    public function parse(string $data): array
    {
        $shifts = json_decode(
            json: $data,
            associative: true,
        );

        if (!is_array($shifts)) {
            throw new ParseException('Unable to parse the given JSON data.');
        }

        $employeeShifts = [];
        foreach ($shifts as $shift) {
            try {
                $shiftData = $this->shiftNormalizer->normalize($shift);
            } catch (NormalizerException $e) {
                throw new ParseException(
                    message: sprintf('Unable to parse shift data. %s', $e->getMessage()),
                    previous: $e,
                );
            }

            $employeeShifts[$shiftData->getEmployeeId()][] = $shiftData;
        }

        return array_map(
            fn(int $employeeId) => new EmployeeShifts(
                $employeeId,
                $employeeShifts[$employeeId],
            ),
            array_keys($employeeShifts),
        );
    }
}
