<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class Node extends Runtime
{
    public function getName(): string
    {
        return 'node';
    }

    /**
     * @return array<string>
     */
    public function getLanguages(): array
    {
        return ['JavaScript', 'TypeScript'];
    }

    public function getCommands(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn install && yarn build',
            'pnpm' => 'pnpm install && pnpm run build',
            default => 'npm install && npm run build',
        };
    }

    /**
     * @return array<string>
     */
    public function getFileExtensions(): array
    {
        return ['js', 'ts'];
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array
    {
        return ['package-lock.json', 'yarn.lock', 'tsconfig.json'];
    }

    public function getEntrypoint(): string
    {
        return 'index.js';
    }
}
