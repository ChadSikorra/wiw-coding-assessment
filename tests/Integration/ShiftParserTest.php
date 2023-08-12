<?php

declare(strict_types=1);

namespace Chad\WhenIWork\Tests\Integration;

use Chad\WhenIWork\EmployeeShifts;
use Chad\WhenIWork\ShiftParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShiftParser::class)]
class ShiftParserTest extends TestCase
{
    private const DATASET_FILE = __DIR__ . '/../../resources/dataset.json';

    /**
     * Extremely simplistic just to verify it goes through the whole data set.
     */
    public function testItParsesTheGivenDataset(): void
    {
        $subject = new ShiftParser();

        $rawData = file_get_contents(self::DATASET_FILE);
        $results = $subject->parse($rawData);

        $this->assertSame(
            expected: 733,
            actual: count($results),
        );
        $this->assertInstanceOf(
            EmployeeShifts::class,
            $results[array_key_first($results)]
        );
    }
}
