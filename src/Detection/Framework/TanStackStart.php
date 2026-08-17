<?php

namespace Utopia\Detector\Detection\Framework;

class TanStackStart extends React
{
    protected string $config = '';

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

    private function strip(string $config): string
    {
        $config = \preg_replace('/\/\*[\s\S]*?\*\//', '', $config) ?? $config;

        // An odd number of quotes ahead of a // puts it inside a string, and
        // nitro is named inside an import string.
        return \preg_replace_callback('/^(.*?)\/\/.*$/m', function (array $matches): string {
            $quotes = \substr_count($matches[1], '"') + \substr_count($matches[1], "'") + \substr_count($matches[1], '`');

            return $quotes % 2 === 0 ? $matches[1] : $matches[0];
        }, $config) ?? $config;
    }

    private function usesNitro(): bool
    {
        // The scaffold registers the plugin, so an unread config is nitro.
        if ($this->config === '') {
            return true;
        }

        return (bool) \preg_match('/nitro\/vite|nitroV2Plugin|@tanstack\/nitro-v2-vite-plugin/', $this->strip($this->config));
    }

    public function getAdapter(string $configContent): string
    {
        $stripped = $this->strip($configContent);

        if (!\preg_match('/\bprerender\b/', $stripped) || \preg_match('/\bprerender[\x27\x22]?\s*:\s*false\b/', $stripped)) {
            return 'ssr';
        }

        // A narrowed prerender leaves the rest of the site to a server.
        if (\preg_match('/\bprerender\b.{0,400}?\b(routes|filter)\s*:/s', $stripped)) {
            return 'ssr';
        }

        return 'static';
    }
}
