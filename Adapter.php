<?php

namespace Utopia\Detector;

abstract class Adapter
{
    abstract public function detect(array $inputs): ?Detection;
}
