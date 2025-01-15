<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Adapter;
use Utopia\Detector\Detection\PackageManager\Detection as PackageManagerDetection;
use Utopia\Detector\Detection\PackageManager\Npm;

class PackageManager implements Adapter
{
    // add param input for mixed input
    public function detect(array $inputs): ?PackageManagerDetection
    {
        // implement logic to detect package manager

        return new Npm();
    }
}
