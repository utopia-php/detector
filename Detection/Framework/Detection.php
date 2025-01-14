<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection as BaseDetection;
use Utopia\Detector\Detection\Models\PackageManagerType;

abstract class Detection extends BaseDetection
{
    protected PackageManagerType $packageManagerType;

    public function __construct(PackageManagerType $packageManagerType)
    {
        $this->packageManagerType = $packageManagerType;
    }

    abstract public function getInstallCommand(): string;
    abstract public function getBuildCommand(): string;
    abstract public function getInstallCommands(): array;
    abstract public function getBuildCommands(): array;
    abstract public function getOutputDirectory(): string;
    abstract public function getFallbackFile(): string | null;
}
