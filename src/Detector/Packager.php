<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Packager as PackagerDetection;
use Utopia\Detector\Detection\Packager\Npm;
use Utopia\Detector\Detection\Packager\Pnpm;
use Utopia\Detector\Detection\Packager\Yarn;
use Utopia\Detector\Detector;

class Packager extends Detector
{
    public function __construct(protected array $inputs) {}

    public function detect(): ?PackagerDetection
    {
        // Check for Yarn lock file
        if (in_array('yarn.lock', $this->inputs)) {
            return new Yarn;
        }

        // Check for Pnpm lock file
        if (in_array('pnpm-lock.yaml', $this->inputs)) {
            return new Pnpm;
        }

        // Check for Package.json file
        if (in_array('package.json', $this->inputs)) {
            return new Npm;
        }

        // No package manager detected
        return null;
    }
}
