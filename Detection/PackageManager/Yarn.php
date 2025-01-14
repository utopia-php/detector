<?php

namespace Utopia\Detector\Detection\PackageManager;

use Utopia\Detector\Detection\PackageManager\Detection as PackageManagerDetection;

class Yarn extends PackageManagerDetection
{
    public function getName(): string
    {
        return 'yarn';
    }
}
