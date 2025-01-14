<?php

namespace Utopia\Detector\Detection\RenderingStrategy;

use Utopia\Detector\Detection\RenderingStrategy\Detection as RenderingStrategyDetection;

class SSG extends RenderingStrategyDetection
{
    public function getName(): string
    {
        return 'ssg';
    }
}
