<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework\Detection as FrameworkDetection;

class NextJs extends FrameworkDetection
{
    public function getName(): string
    {
        return 'Next.js';
    }

    public function getInstallCommand(): string
    {
        return 'npm install';
    }

    public function getBuildCommand(): string
    {
        return 'npm run build';
    }

    public function getInstallCommands(): array
    {
        return [
            'npm' => 'npm install',
            'yarn' => 'yarn install',
            'pnpm' => 'pnpm install',
        ];
    }

    public function getBuildCommands(): array
    {
        return [
            'npm' => 'npm run build',
            'yarn' => 'yarn build',
            'pnpm' => 'pnpm build',
        ];
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
