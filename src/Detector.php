<?php

namespace Utopia\Detector;

abstract class Detector
{
    public function __construct(protected array $inputs) {}

    abstract public function detect(): ?Detection;
}
