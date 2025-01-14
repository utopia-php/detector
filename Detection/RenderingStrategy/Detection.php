<?php

namespace Utopia\Detector\Detection\RenderingStrategy;

use Utopia\Detector\Detection as BaseDetection;

abstract class Detection extends BaseDetection
{
    abstract public function getName(): string;
}
