<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Framework extends Detection
{
    protected string $packager = '';

    public function __construct()
    {
    }

    public function setPackager(string $packager): self
    {
        $this->packager = $packager;

        return $this;
    }

    abstract public function getName(): string;

    /**
     * @return array<string> List of relevant files for detection.
     */
    abstract public function getFiles(): array;

    abstract public function getInstallCommand(): string;

    abstract public function getBuildCommand(): string;

    abstract public function getOutputDirectory(): string;
}
