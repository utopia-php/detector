<?php

namespace Utopia\Detector\Detection\Packager;

use Utopia\Detector\Detection\Packager;

class Pnpm extends Packager
{
    public function getName(): string
    {
        return 'pnpm';
    }
}
