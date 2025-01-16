<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Detection\PackageManager\Detection as PackageManagerDetection;
use Utopia\Detector\Detection\PackageManager\Npm;
use Utopia\Detector\Detector;

class PackageManager extends Detector
{
    // add param input for mixed input
    public function detect(): ?PackageManagerDetection
    {
        // implement logic to detect package manager

        return new Npm;
    }
}
