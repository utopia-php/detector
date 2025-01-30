<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class Deno extends Runtime
{
    public function getName(): string
    {
        return 'deno';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['TypeScript'];
    }

    public function getCommands(): string
    {
        return 'deno cache main.ts';
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['ts', 'tsx'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['mod.ts', 'deps.ts'];
    }

    public function getEntrypoint(): string
    {
        return 'main.ts';
    }
}
