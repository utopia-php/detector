<?php

namespace Utopia\Detector;

use Utopia\Detector\Detection\Runtime as RuntimeDetection;
use Utopia\Detector\Detection\Runtime\Node;
use Utopia\Detector\Detector;

class Runtime extends Detector
{
    public function __construct(protected array $inputs, protected string $packager)
    {
    }

    public function detect(): ?RuntimeDetection
    {
        // TODO: Implement detection using $this->files
        return new Node($this->packager);
    }
}
