<?php

namespace Utopia\Detector;

abstract class Detector
{
    private array $inputs;

    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
    }

    abstract public function detect(): ?Detection;
}
