<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class JavaScript extends Runtime
{
    public function getName(): string
    {
        return 'swift';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['Swift'];
    }

    public function getCommand(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn install',
            'pnpm' => 'pnpm install',
            default => 'npm install',
        };
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['swift', 'xcodeproj', 'xcworkspace'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['Package.swift', 'Podfile', 'project.pbxproj'];
    }

    public function getEntrypoint(): string
    {
        return 'main.swift';
    }
}
