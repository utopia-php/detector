<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Adapter;
use Utopia\Detector\Detection\Models\FrameworkType;
use Utopia\Detector\Detection\Rendering\Detection as RenderingDetection;
use Utopia\Detector\Detection\Rendering\SSR;

class Rendering implements Adapter
{
    // add param input for mixed input
    public function detect(array $inputs, FrameworkType $frameworkType = FrameworkType::NEXTJS): ?RenderingDetection
    {
        // implement logic to detect rendering strategy ie server side, client side etc using framework type

        return new SSR();
    }
}
