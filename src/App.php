<?php

declare(strict_types=1);

namespace Chad\WhenIWork;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class App
{
    private const OPT_SHIFT_FILE = 'shift-file';

    public function __construct(
        private readonly ShiftParser $shiftParser = new ShiftParser(),
        private readonly Reporter $reporter = new Reporter(),
    ) {
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $datasetFile = $input->getArgument(self::OPT_SHIFT_FILE);
        if (!is_string($datasetFile) || !file_exists($datasetFile)) {
            $output->writeln('Unable to find the shift report dataset file.');

            return 1;
        }

        try {
            $shifts = $this->shiftParser->parse((string) file_get_contents($datasetFile));
        } catch (Throwable $e) {
            $output->writeln(sprintf(
                'Failed to parse shift data. %s',
                $e->getMessage(),
            ));

            return 1;
        }

        $output->writeln($this->reporter->report($shifts));

        return 0;
    }
}
