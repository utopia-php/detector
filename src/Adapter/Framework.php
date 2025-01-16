<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Detection\Framework\Detection as FrameworkDetection;
use Utopia\Detector\Detection\Framework\NextJs;
use Utopia\Detector\Detection\Models\PackageManagerType;
use Utopia\Detector\Detector;

class Framework extends Detector
{
    public function detect(PackageManagerType $packageManagerType = PackageManagerType::NPM): ?FrameworkDetection
    {
        // Implement logic to determine if framework matches

        return new NextJs($packageManagerType);
    }
}
