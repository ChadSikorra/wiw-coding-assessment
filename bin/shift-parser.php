<?php

declare(strict_types=1);

use Chad\WhenIWork\App;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\SingleCommandApplication;

require_once __DIR__ . '/../vendor/autoload.php';

(new SingleCommandApplication)
    ->setName('Shift Parser')
    ->setVersion('1.0.0')
    ->addArgument(
        name: 'shift-file',
        mode: InputArgument::OPTIONAL,
        default: __DIR__ . '/../resources/dataset.json',
    )
    ->setCode(new App())
    ->run()
;
