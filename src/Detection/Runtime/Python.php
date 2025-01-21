<?php

namespace Utopia\Detector\Detection;

class Python extends Runtime
{
    public function getName(): string
    {
        return 'python';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['Python'];
    }

    public function getCommand(): string
    {
        return 'pip install';
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['py'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['requirements.txt', 'setup.py'];
    }

    public function getEntrypoint(): string
    {
        return 'main.py';
    }
}
