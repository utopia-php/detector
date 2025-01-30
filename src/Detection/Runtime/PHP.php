<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class PHP extends Runtime
{
    public function getName(): string
    {
        return 'php';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['PHP'];
    }

    public function getCommands(): string
    {
        return 'composer install && composer run build';
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['php'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['composer.json', 'composer.lock'];
    }

    public function getEntrypoint(): string
    {
        return 'index.php';
    }
}
