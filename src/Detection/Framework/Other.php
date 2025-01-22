<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Other extends Framework
{
    public function getName(): string
    {
        return 'other';
    }

    public function getFiles(): array
    {
        return ['index.html'];
    }

    public function getFileExtensions(): array
    {
        return ['html', 'htm'];
    }

    public function getLanguages(): array
    {
        return ['HTML'];
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
