<?php

namespace Utopia\Detector\Detection\Rendering;

use Utopia\Detector\Detection\Rendering\Detection as RenderingDetection;

class SSR extends RenderingDetection
{
    public function getName(): string
    {
        return 'ssr';
    }
}
