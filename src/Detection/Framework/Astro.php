<?php

namespace Utopia\Detector\Detection\Framework;

class Astro extends JS
{
    public function getName(): string
    {
        return 'astro';
    }

    /**
     * @return array<string>
     */
    public function getPackages(): array
    {
        $packages = \array_merge(
            ['astro'],
            parent::getPackages(),
            (new Angular())->getPackages(),
            (new React())->getPackages(),
            (new Vue())->getPackages(),
            (new Svelte())->getPackages()
        );

        return \array_unique($packages);
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array
    {
        $files = \array_merge(
            ['astro.config.mjs', 'astro.config.js', 'astro.config.ts'],
            parent::getFiles(),
            (new Angular())->getFiles(),
            (new React())->getFiles(),
            (new Vue())->getFiles(),
            (new Svelte())->getFiles()
        );

        return \array_unique($files);
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
