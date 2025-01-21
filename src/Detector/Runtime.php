<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Runtime as DetectionRuntime;
use Utopia\Detector\Detection\Runtime\JavaScript;
use Utopia\Detector\Detector;

class Runtime extends Detector
{
    public function __construct(protected array $inputs, protected string $packager) {}

    public function detect(): ?DetectionRuntime
    {
        // TODO: Implement detection using $this->files
        return new JavaScript($this->packager);
    }
}
