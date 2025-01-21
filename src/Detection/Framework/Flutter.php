<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Flutter extends Framework
{
    public function getName(): string
    {
        return 'Flutter';
    }

    public function getInstallCommand(): string
    {
        return '';
    }

    public function getBuildCommand(): string
    {
        return 'flutter build web';
    }

    public function getOutputDirectory(): string
    {
        return './build/web';
    }

    public function getFallbackFile(): ?string
    {
        return null;
    }
}
