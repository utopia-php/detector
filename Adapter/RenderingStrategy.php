<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Adapter;
use Utopia\Detector\Detection\Models\FrameworkType;
use Utopia\Detector\Detection\RenderingStrategy\Detection as RenderingStrategyDetection;
use Utopia\Detector\Detection\RenderingStrategy\SSR;

class RenderingStrategy implements Adapter
{
    // add param input for mixed input
    public function detect(array $inputs, FrameworkType $frameworkType = FrameworkType::NEXTJS): ?RenderingStrategyDetection
    {
        // implement logic to detect rendering strategy ie server side, client side etc using framework type

        return new SSR();
    }
}
