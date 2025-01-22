<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class Dart extends Runtime
{
    public function getName(): string
    {
        return 'dart';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['Dart'];
    }

    public function getCommand(): string
    {
        return 'dart pub get && dart compile exe lib/main.dart -o main';
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['dart'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['pubspec.yaml', 'pubspec.lock'];
    }

    public function getEntrypoint(): string
    {
        return 'lib/main.dart';
    }
}
