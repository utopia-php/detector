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
            'npm' => 'npm install',
            default => 'pnpm install',
        };
    }

    public function getBuildCommand(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn build',
            'pnpm' => 'pnpm run build',
            'npm' => 'npm run build',
            default => 'pnpm run build',
        };
    }

    public function getOutputDirectory(): string
    {
        return './dist';
    }

    public function getConfigFiles(): array
    {
        return ['astro.config.mjs', 'astro.config.js', 'astro.config.ts'];
    }

    public function getAdapter(string $configContent): string
    {
        // 'server' and 'hybrid' both require the SSR adapter
        if (\str_contains($configContent, "output: 'server'") || \str_contains($configContent, 'output: "server"')) {
            return 'ssr';
        }
        if (\str_contains($configContent, "output: 'hybrid'") || \str_contains($configContent, 'output: "hybrid"')) {
            return 'ssr';
        }

        return 'static';
    }
}
