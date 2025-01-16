<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Detection\Models\PackageManagerType;
use Utopia\Detector\Detection\Runtime\Detection as RuntimeDetection;
use Utopia\Detector\Detection\Runtime\Node;
use Utopia\Detector\Detector;

class Runtime extends Detector
{
    // add param input for mixed input
    public function detect(PackageManagerType $packageManagerType = PackageManagerType::NPM): ?RuntimeDetection
    {
        // let's assume it detected node and wants to return the runtime detection
        return new Node($packageManagerType);
    }
}
