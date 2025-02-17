<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Rendering extends Detection
{
    protected ?string $fallbackFile;

    public function __construct(?string $fallbackFile = null)
    {
        $this->fallbackFile = $fallbackFile;
    }

    public function getFallbackFile(): ?string
    {
        return $this->fallbackFile;
    }

    abstract public function getName(): string;
}
