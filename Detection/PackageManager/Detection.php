<?php

namespace Utopia\Detector\Detection\PackageManager;

use Utopia\Detector\Detection as BaseDetection;

abstract class Detection extends BaseDetection
{
    abstract public function getName(): string;
}