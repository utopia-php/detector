<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Remix extends Framework
{
    public function getName(): string
    {
        return 'remix';
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array
    {
        return ['remix.config.js', 'remix.config.ts', 'app/root.jsx', 'app/root.tsx', 'vite.config.ts', 'vite.config.mjs'];
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
