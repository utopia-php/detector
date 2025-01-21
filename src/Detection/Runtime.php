<?php

namespace Utopia\Detector\Detection;

use Utopia\Detector\Detection;

abstract class Runtime extends Detection
{
    public function __construct(protected string $packager) {}

    abstract public function getName(): string;

    abstract public function getLanguages(): array;

    abstract public function getCommand(): string;

    abstract public function getFileExtensions(): array;

    abstract public function getFiles(): array;

    abstract public function getEntrypoint(): string;
}
