<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Runtime extends Detection
{
    protected string $packager = '';

    public function __construct() {}

    public function setPackager(string $packager): self
    {
        $this->packager = $packager;

        return $this;
    }

    abstract public function getName(): string;

    abstract public function getLanguages(): array;

    abstract public function getCommands(): string;

    abstract public function getFileExtensions(): array;

    abstract public function getFiles(): array;

    abstract public function getEntrypoint(): string;
}
