<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

use RuntimeException;

class Reporter
{
    /**
     * @param EmployeeShifts[] $employeeShifts
     */
    public function report(array $employeeShifts): string
    {
        $reportData = [];

        foreach ($employeeShifts as $employeeShift) {
            $reportData = [
                ...$reportData,
                ...$this->generateByWeek($employeeShift),
            ];
        }

        $encoded = json_encode($reportData);
        if (!is_string($encoded)) {
            throw new RuntimeException('Unable to encode shift report data.');
        }

        return $encoded;
    }

    /**
     * @return array<array{EmployeeID: int, StartOfWeek: string, RegularHours: float, OvertimeHours: float, InvalidShifts: int[]}>
     */
    private function generateByWeek(EmployeeShifts $employeeShifts): array
    {
        $weeklyReportData = [];

        foreach ($employeeShifts->getWeeklyData() as $weeklyData) {
            $weeklyReportData[] = [
                'EmployeeID' => $employeeShifts->getEmployeeId(),
                'StartOfWeek' => $weeklyData->getStartOfWeek(),
                'RegularHours' => $weeklyData->getRegularHours(),
                'OvertimeHours' => $weeklyData->getOvertimeHours(),
                'InvalidShifts' => $weeklyData->getInvalidShifts(),
            ];
        }

        return $weeklyReportData;
    }
}
