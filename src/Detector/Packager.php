<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Packager as PackagerDetection;
use Utopia\Detector\Detection\Packager\Npm;
use Utopia\Detector\Detector;

class Packager extends Detector
{
    public function detect(): ?PackagerDetection
    {
        // TODO: Implement detection using $this->files
        return new Npm();
    }
}
