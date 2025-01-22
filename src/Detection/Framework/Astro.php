<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Astro extends Framework
{
    public function getName(): string
    {
        return 'Astro';
    }

    public function getFiles(): array
    {
        return ['astro.config.mjs', 'astro.config.js', '.astro/'];
    }

    public function getFileExtensions(): array
    {
        return ['astro', 'js', 'ts'];
    }

    public function getLanguages(): array
    {
        return ['JavaScript', 'TypeScript', 'Astro'];
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
        return './dist';
    }
}
