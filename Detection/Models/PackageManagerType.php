<?php

namespace Utopia\Detector\Detection\Models;

enum PackageManagerType: string
{
    case NPM = 'npm';
    case YARN = 'yarn';
    case PNPM = 'pnpm';
}
