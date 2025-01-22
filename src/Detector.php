<?php

namespace Utopia\Detector;

abstract class Detector
{
    protected array $detectors = [];

    public function __construct(protected array $inputs) {}

    abstract public function detect(): ?Detection;

    public function addDetector(Detection $detector): self
    {
        $this->detectors[] = $detector;

        return $this;
    }
}
