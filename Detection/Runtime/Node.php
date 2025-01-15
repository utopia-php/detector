<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Models\PackageManagerType;
use Utopia\Detector\Detection\Runtime\Detection as RuntimeDetection;

class Node extends RuntimeDetection
{
    public function getName(): string
    {
        return 'node';
    }

    public function getCommand(): string
    {
        return match ($this->packageManagerType) {
            PackageManagerType::NPM => 'npm install',
            PackageManagerType::YARN => 'yarn install',
            PackageManagerType::PNPM => 'pnpm install',
        };
    }
}
