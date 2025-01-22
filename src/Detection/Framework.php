<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Framework extends Detection
{
    public function __construct(protected string $packager) {}

    abstract public function getName(): string;

    abstract public function getFiles(): array;

    abstract public function getFileExtensions(): array;

    abstract public function getLanguages(): array;

    abstract public function getInstallCommand(): string;

    abstract public function getBuildCommand(): string;

    abstract public function getOutputDirectory(): string;
}
