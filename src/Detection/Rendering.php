<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Rendering extends Detection
{
    abstract public function getName(): string;
}
