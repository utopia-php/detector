<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class SvelteKit extends Framework
{
    public function getName(): string
    {
        return 'SvelteKit';
    }

    public function getFiles(): array
    {
        return ['svelte.config.js', 'vite.config.js', 'vite.config.mjs', 'vite.config.ts'];
    }

    public function getFileExtensions(): array
    {
        return ['js', 'ts', 'svelte'];
    }

    public function getLanguages(): array
    {
        return ['JavaScript', 'TypeScript', 'Svelte'];
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
        return './build';
    }
}
