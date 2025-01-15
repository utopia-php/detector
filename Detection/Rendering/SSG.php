<?php

namespace Utopia\Detector\Detection\Rendering;

use Utopia\Detector\Detection\Rendering\Detection as RenderingDetection;

class SSG extends RenderingDetection
{
    public function getName(): string
    {
        return 'ssg';
    }
}
