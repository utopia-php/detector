<?php

namespace Utopia\Detector\Detection\Packager;

use Utopia\Detector\Detection\Packager;

class PNPM extends Packager
{
    public function getName(): string
    {
        return 'pnpm';
    }

    public function getFiles(): array
    {
        return ['pnpm-lock.yaml'];
    }
}
