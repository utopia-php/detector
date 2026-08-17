<?php

namespace Utopia\Detector\Detection\Framework;

class TanStackStart extends React
{
    protected string $config = '';

    /**
     * Vite config content, when the caller was able to read it. Lets
     * getOutputDirectory tell the two build layouts apart.
     */
    public function setConfig(string $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function getName(): string
    {
        return 'tanstack-start';
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array
    {
        return \array_merge([], parent::getFiles());
    }

    /**
     * @return array<string>
     */
    public function getPackages(): array
    {
        return \array_merge(['@tanstack/react-start', '@tanstack/solid-start'], parent::getPackages());
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

    /**
     * Nitro writes .output, serving assets from .output/public. Without it the
     * vite plugin writes dist, serving assets from dist/client.
     */
    public function getOutputDirectory(): string
    {
        $static = $this->getAdapter($this->config) === 'static';

        if ($this->usesNitro()) {
            return $static ? './.output/public' : './.output';
        }

        return $static ? './dist/client' : './dist';
    }

    /**
     * @return array<string>
     */
    public function getConfigFiles(): array
    {
        return ['vite.config.ts', 'vite.config.js', 'vite.config.mjs'];
    }

    /**
     * Nitro writes .output; without it the vite plugin writes dist. Assume
     * nitro when the config could not be read, matching what the official
     * scaffold generates.
     */
    private function usesNitro(): bool
    {
        if ($this->config === '') {
            return true;
        }

        $stripped = \preg_replace('/(?<!:)\/\/[^\n]*/', '', $this->config) ?? $this->config;

        return (bool) \preg_match('/nitro\/vite|nitroV2Plugin|@tanstack\/nitro-v2-vite-plugin/', $stripped);
    }

    public function getAdapter(string $configContent): string
    {
        $stripped = \preg_replace('/(?<!:)\/\/[^\n]*/', '', $configContent) ?? $configContent;

        if (!\preg_match('/\bprerender\b/', $stripped) || \preg_match('/\bprerender[\x27\x22]?\s*:\s*false\b/', $stripped)) {
            return 'ssr';
        }

        return 'static';
    }
}
