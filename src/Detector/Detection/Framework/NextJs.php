<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class NextJs extends Framework
{
    public function getName(): string
    {
        return 'Next.js';
    }

    public function getInstallCommand(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn install',
            'pnpm' => 'pnpm install',
            default => 'npm install',
        };
    }

    public function getBuildCommand(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn build',
            'pnpm' => 'pnpm build',
            default => 'npm run build',
        };
    }

    public function getOutputDirectory(): string
    {
        return './.next';
    }

    public function getFallbackFile(): ?string
    {
        return null;
    }
}
