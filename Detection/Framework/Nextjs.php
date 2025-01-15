<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework\Detection as FrameworkDetection;
use Utopia\Detector\Detection\Models\PackageManagerType;

class NextJs extends FrameworkDetection
{
    public function getName(): string
    {
        return 'Next.js';
    }

    public function getInstallCommand(): string
    {
        return match ($this->packageManagerType) {
            PackageManagerType::NPM => 'npm install',
            PackageManagerType::YARN => 'yarn install',
            PackageManagerType::PNPM => 'pnpm install',
        };
    }

    public function getBuildCommand(): string
    {
        return match ($this->packageManagerType) {
            PackageManagerType::NPM => 'npm run build',
            PackageManagerType::YARN => 'yarn build',
            PackageManagerType::PNPM => 'pnpm build',
        };
    }

    public function getOutputDirectory(): string
    {
        return './.next';
    }

    public function getFallbackFile(): string | null
    {
        return null;
    }
}
