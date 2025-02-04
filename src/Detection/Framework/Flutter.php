<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection\Framework;

class Flutter extends Framework
{
    public function getName(): string
    {
        return 'flutter';
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array // TODO: root level only and no nesting
    {
        return ['pubspec.yaml', 'pubspec.lock', 'lib/main.dart', 'android/', 'ios/', 'web/']; // TODO: check what github returns
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
