<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class XStatic extends Framework
{
    public function getName(): string
    {
        return 'static';
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

    public function getFallbackFile(): ?string
    {
        return 'index.html';
    }
}
