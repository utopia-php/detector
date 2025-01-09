<?php

namespace Utopia\Detector\Detection\Framework;

use Utopia\Detector\Detection as BaseDetection;

abstract class Detection extends BaseDetection
{
    abstract public function getInstallCommand(): string;
    abstract public function getBuildCommand(): string;
    abstract public function getInstallCommands(): array;
    abstract public function getBuildCommands(): array;
    abstract public function getOutputDirectory(): string;
    abstract public function getFallbackFile(): string | null;
}