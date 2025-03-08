<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Rendering extends Detection
{
    public function __construct(protected ?string $fallbackFile = null)
    {
        $this->fallbackFile = $fallbackFile;
    }

    public function getFallbackFile(): ?string
    {
        return $this->fallbackFile;
    }

    abstract public function getName(): string;
}
