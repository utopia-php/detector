<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime\Detection as RuntimeDetection;

class Node extends RuntimeDetection
{
    public function getName(): string
    {
        return 'node';
    }

    public function getCommand(): string
    {
        return 'npm install';
    }

    public function getCommands(): array
    {
        return [
            'npm' => 'npm install',
            'yarn' => 'yarn install',
            'pnpm' => 'pnpm install',
        ];
    }
}
