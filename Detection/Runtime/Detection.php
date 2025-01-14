<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection as BaseDetection;
use Utopia\Detector\Detection\Models\PackageManagerType;

abstract class Detection extends BaseDetection
{
    protected PackageManagerType $packageManagerType;

    public function __construct(PackageManagerType $packageManagerType)
    {
        $this->packageManagerType = $packageManagerType;
    }

    abstract public function getCommand(): string;
    abstract public function getCommands(): array;
}
