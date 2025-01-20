<?php

namespace Utopia\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\SSR;

class Rendering extends Detector
{
    public function detect(): ?RenderingDetection
    {
        // TODO: Implement detection using $this->files
        return new SSR();
    }
}
