<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Adapter;
use Utopia\Detector\Detection\RenderingStrategy\Detection as RenderingStrategyDetection;

class RenderingStrategy implements Adapter
{
    // add param input for mixed input
    public function detect(array $inputs): ?RenderingStrategyDetection
    {
        // implement logic to detect rendering strategy ie server side, client side, etc

        return null;
    }
}