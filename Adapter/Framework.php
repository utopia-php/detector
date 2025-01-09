<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Adapter;
use Utopia\Detector\Detection\Framework\Detection as FrameworkDetection;

class Framework implements Adapter
{
    // add param input for mixed input
    public function detect(array $inputs): ?FrameworkDetection
    {
        // Implement logic to determine if framework matches

        return null;
    }
}