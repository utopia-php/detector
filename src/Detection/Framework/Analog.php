<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Analog extends Framework
{
    public function getName(): string
    {
        return 'analog';
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array
    {
        return ['vite.config.js', 'vite.config.ts', 'angular.json'];
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
        return './dist/analog';
    }
}
