<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Other extends Framework
{
    public function getName(): string
    {
        return 'other';
    }

    public function getInstallCommand(): string
    {
        return '';
    }

    public function getBuildCommand(): string
    {
        return '';
    }

    public function getOutputDirectory(): string
    {
        return './';
    }
}
