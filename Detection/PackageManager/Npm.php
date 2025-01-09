<?php

namespace Utopia\Detector\Detection\PackageManager;

use Utopia\Detector\Detection\PackageManager\Detection as PackageManagerDetection;

class Npm extends PackageManagerDetection
{
    public function getName(): string
    {
        return 'npm';
    }
}