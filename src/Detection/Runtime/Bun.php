<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class Bun extends Runtime
{
    public function getName(): string
    {
        return 'bun';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['JavaScript', 'TypeScript'];
    }

    public function getCommands(): string
    {
        return 'bun install && bun build';
    }
    // TODO: Verify commands for all runtimes

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['ts', 'tsx', 'js', 'jsx'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['bun.lockb'];
    }

    public function getEntrypoint(): string
    {
        return 'main.ts';
    }
}
