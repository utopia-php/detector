<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class Node extends Runtime
{
    public function getName(): string
    {
        return 'node';
    }

    public function getCommand(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn install',
            'pnpm' => 'pnpm install',
            default => 'npm install',
        };
    }
}
