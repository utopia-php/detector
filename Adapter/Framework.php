<?php

namespace Utopia\Detector\Adapter;

use Utopia\Detector\Adapter;
use Utopia\Detector\Detection\Framework\Detection as FrameworkDetection;
use Utopia\Detector\Detection\Framework\NextJs;
use Utopia\Detector\Detection\Models\PackageManagerType;

class Framework implements Adapter
{
    // add param input for mixed input
    public function detect(array $inputs, PackageManagerType $packageManagerType = PackageManagerType::NPM): ?FrameworkDetection
    {
        // Implement logic to determine if framework matches

        return new NextJs($packageManagerType);
    }
}
