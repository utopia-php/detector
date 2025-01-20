<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Packager extends Detection
{
    abstract public function getName(): string;
}
