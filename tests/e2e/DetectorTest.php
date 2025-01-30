<?php

namespace Utopia\Tests;

use PHPUnit\Framework\TestCase;
use Utopia\Detector\Detection\Packager\Npm;
use Utopia\Detector\Detection\Packager\Pnpm;
use Utopia\Detector\Detection\Packager\Yarn;
use Utopia\Detector\Detection\Runtime\Bun;
use Utopia\Detector\Detection\Runtime\Cpp;
use Utopia\Detector\Detection\Runtime\Dart;
use Utopia\Detector\Detection\Runtime\Deno;
use Utopia\Detector\Detection\Runtime\Dotnet;
use Utopia\Detector\Detection\Runtime\Java;
use Utopia\Detector\Detection\Runtime\Node;
use Utopia\Detector\Detection\Runtime\PHP;
use Utopia\Detector\Detection\Runtime\Python;
use Utopia\Detector\Detection\Runtime\Ruby;
use Utopia\Detector\Detection\Runtime\Swift;
use Utopia\Detector\Detector\Packager;
use Utopia\Detector\Detector\Runtime;
use Utopia\Detector\Detector\Strategy;

class DetectorTest extends TestCase
{
    public function test_detect_packager(): void // TODO: Rename to testDetectPackager
    {
        foreach ($this->packagerDataProvider() as $data) {
            $detector = new Packager($data['files']);
            $detector
                ->addOption(new Pnpm)
                ->addOption(new Yarn)
                ->addOption(new Npm);

            $packager = $detector->detect();
            if ($packager) {
                $this->assertEquals($data['packager'], $packager->getName());
            } else {
                $this->assertEquals($data['packager'], null);
            }
        }
    }

    public function packagerDataProvider(): array
    {
        return [
            [
                'files' => ['bun.lockb', 'fly.toml', 'package.json', 'remix.config.js'],
                'packager' => 'npm',
            ],
            [
                'files' => ['yarn.lock'],
                'packager' => 'yarn',
            ],
            [
                'files' => ['pnpm-lock.yaml'],
                'packager' => 'pnpm',
            ],
            // test for FAILURE
            [
                'files' => ['composer.json'],
                'packager' => null,
            ],
        ];
    }

    public function test_detect_runtime_by_filematch(): void
    {
        foreach ($this->runtimeDataProviderByFilematch() as $data) {
            $detector = new Runtime(
                $data['files'],
                new Strategy(Strategy::FILEMATCH),
                $data['packager'] ?? 'npm'
            );

            $detector
                ->addOption(new Node)
                ->addOption(new Bun)
                ->addOption(new Deno)
                ->addOption(new PHP)
                ->addOption(new Python)
                ->addOption(new Dart)
                ->addOption(new Swift)
                ->addOption(new Ruby)
                ->addOption(new Java)
                ->addOption(new Cpp)
                ->addOption(new Dotnet);

            $runtime = $detector->detect();
            if ($runtime) {
                $this->assertEquals($data['runtime'], $runtime->getName());
                $this->assertEquals($data['commands'], $runtime->getCommands());
            } else {
                $this->assertEquals($data['runtime'], null);
                $this->assertEquals($data['commands'], null);
            }
        }
    }

    public function runtimeDataProviderByFilematch(): array
    {
        return [
            [
                'files' => ['package-lock.json', 'yarn.lock', 'tsconfig.json'],
                'runtime' => 'node',
                'commands' => 'npm install && npm run build',
            ],
            [
                'files' => ['package-lock.json', 'yarn.lock', 'tsconfig.json'],
                'runtime' => 'node',
                'commands' => 'yarn install && yarn build',
                'packager' => 'yarn',
            ],
            [
                'files' => ['composer.json', 'composer.lock'],
                'runtime' => 'php',
                'commands' => 'composer install && composer run build',
            ],
            [
                'files' => ['pubspec.yaml'],
                'runtime' => 'dart',
                'commands' => 'dart pub get',
            ],
            [
                'files' => ['Gemfile', 'Gemfile.lock'],
                'runtime' => 'ruby',
                'commands' => 'bundle install && bundle exec rake build',
            ],
            // test for FAILURE
            [
                'files' => ['index.html', 'style.css'],
                'runtime' => null,
                'commands' => null,
            ],
        ];
    }

    public function test_detect_runtime_by_languages(): void
    {
        foreach ($this->runtimeDataProviderByLanguages() as $data) {
            $detector = new Runtime(
                $data['files'],
                new Strategy(Strategy::LANGUAGES),
                $data['packager'] ?? 'npm'
            );

            $detector
                ->addOption(new Node)
                ->addOption(new Bun)
                ->addOption(new Deno)
                ->addOption(new PHP)
                ->addOption(new Python)
                ->addOption(new Dart)
                ->addOption(new Swift)
                ->addOption(new Ruby)
                ->addOption(new Java)
                ->addOption(new Cpp)
                ->addOption(new Dotnet);

            $runtime = $detector->detect();
            if ($runtime) {
                $this->assertEquals($data['runtime'], $runtime->getName());
                $this->assertEquals($data['commands'], $runtime->getCommands());
            } else {
                $this->assertEquals($data['runtime'], null);
                $this->assertEquals($data['commands'], null);
            }
        }
    }

    public function runtimeDataProviderByLanguages(): array
    {
        return [
            [
                'files' => ['TypeScript', 'JavaScript', 'DockerFile'],
                'runtime' => 'node',
                'commands' => 'npm install && npm run build',
            ],
            [
                'files' => ['TypeScript', 'JavaScript', 'DockerFile'],
                'runtime' => 'node',
                'commands' => 'yarn install && yarn build',
                'packager' => 'yarn',
            ],
            // test for FAILURE
            [
                'files' => ['HTML'],
                'runtime' => null,
                'commands' => null,
            ],
        ];
    }

    public function test_detect_runtime_by_file_extensions(): void
    {
        foreach ($this->runtimeDataProviderByFileExtensions() as $data) {
            $detector = new Runtime(
                $data['files'],
                new Strategy(Strategy::EXTENSION),
                $data['packager'] ?? 'npm'
            );

            $detector
                ->addOption(new Node)
                ->addOption(new Bun)
                ->addOption(new Deno)
                ->addOption(new PHP)
                ->addOption(new Python)
                ->addOption(new Dart)
                ->addOption(new Swift)
                ->addOption(new Ruby)
                ->addOption(new Java)
                ->addOption(new Cpp)
                ->addOption(new Dotnet);

            $runtime = $detector->detect();
            if ($runtime) {
                $this->assertEquals($data['runtime'], $runtime->getName());
                $this->assertEquals($data['commands'], $runtime->getCommands());
            } else {
                $this->assertEquals($data['runtime'], null);
                $this->assertEquals($data['commands'], null);
            }
        }
    }

    public function runtimeDataProviderByFileExtensions(): array
    {
        return [
            [
                'files' => ['main.ts', 'main.js', 'DockerFile'],
                'runtime' => 'node',
                'commands' => 'npm install && npm run build',
            ],
            [
                'files' => ['main.ts', 'main.js', 'DockerFile'],
                'runtime' => 'node',
                'commands' => 'yarn install && yarn build',
                'packager' => 'yarn',
            ],
            [
                'files' => ['composer.json', 'index.php', 'DockerFile'],
                'runtime' => 'php',
                'commands' => 'composer install && composer run build',
            ],
            // test for FAILURE
            [
                'files' => ['index.html', 'style.css'],
                'runtime' => null,
                'commands' => null,
            ],
        ];
    }
}

// TODO: Check for SPA detection
