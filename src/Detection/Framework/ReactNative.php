<?php

namespace Utopia\Detector\Detection\Framework;

class ReactNative extends React
{
    public function getName(): string
    {
        return 'react-native';
    }

    /**
     * @return array<string>
     */
    public function getPackages(): array
    {
        return \array_merge(['react-native'], parent::getPackages());
    }

    /**
     * @return array<string>
     */
    public function getFiles(): array
    {
        return \array_merge([], parent::getFiles());
    }

    public function getInstallCommand(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn install',
            'pnpm' => 'pnpm install',
            default => 'npm install',
        };
    }

    public function getBuildCommand(): string
    {
        return match ($this->packager) {
            'yarn' => 'yarn build',
            'pnpm' => 'pnpm build',
            default => 'npm run build',
        };
    }

    public function getOutputDirectory(): string
    {
        return './dist';
    }
}
