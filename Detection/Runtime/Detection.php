<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection as BaseDetection;

abstract class Detection extends BaseDetection
{
    abstract public function getCommand(): string;
    abstract public function getCommands(): array;
}