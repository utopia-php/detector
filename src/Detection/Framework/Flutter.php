<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Flutter extends Framework
{
    public function getName(): string
    {
        return 'Flutter';
    }

    public function getFiles(): array
    {
        return ['pubspec.yaml', 'pubspec.lock', 'lib/main.dart', 'android/', 'ios/', 'web/'];
    }

    public function getFileExtensions(): array
    {
        return ['dart'];
    }

    public function getLanguages(): array
    {
        return ['Dart'];
    }

    public function getInstallCommand(): string
    {
        return '';
    }

    public function getBuildCommand(): string
    {
        return 'flutter build web';
    }

    public function getOutputDirectory(): string
    {
        return './build/web';
    }
}
