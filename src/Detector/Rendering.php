<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\SSR;
use Utopia\Detector\Detector;

class Rendering extends Detector
{
    public function __construct(protected array $inputs, protected string $framework) {}

    public function detect(): ?RenderingDetection
    {
        // TODO: Implement detection using $this->files
        return new SSR;
    }
}
