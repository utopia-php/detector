<?php

namespace Utopia\Detector\Detection\Packager;

use Utopia\Detector\Detection\Packager;

class Npm extends Packager
{
    public function getName(): string
    {
        return 'npm';
    }

    public function getFiles(): array
    {
        return ['package.json', 'package-lock.json'];
    }
}
