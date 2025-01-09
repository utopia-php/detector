<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Adapter;
use Utopia\Detector\Detection\Runtime\Detection as RuntimeDetection;

class Runtime implements Adapter
{
    // add param input for mixed input
    public function detect(array $inputs): ?RuntimeDetection
    {
        // Implement logic to determine if runtime matches

        return null;
    }
}