<?php

namespace Utopia\Tests;

use PHPUnit\Framework\TestCase;
use Utopia\Detector\Detection\Framework\Astro;
use Utopia\Detector\Detection\Framework\Flutter;
use Utopia\Detector\Detection\Framework\NextJs;
use Utopia\Detector\Detection\Framework\Nuxt;
use Utopia\Detector\Detection\Framework\Remix;
use Utopia\Detector\Detection\Framework\SvelteKit;
use Utopia\Detector\Detection\Packager\NPM;
use Utopia\Detector\Detection\Packager\PNPM;
use Utopia\Detector\Detection\Packager\Yarn;
use Utopia\Detector\Detection\Rendering\SSG;
use Utopia\Detector\Detection\Rendering\SSR;
use Utopia\Detector\Detection\Runtime\Bun;
use Utopia\Detector\Detection\Runtime\CPP;
use Utopia\Detector\Detection\Runtime\Dart;
use Utopia\Detector\Detection\Runtime\Deno;
use Utopia\Detector\Detection\Runtime\Dotnet;
use Utopia\Detector\Detection\Runtime\Java;
use Utopia\Detector\Detection\Runtime\Node;
use Utopia\Detector\Detection\Runtime\PHP;
use Utopia\Detector\Detection\Runtime\Python;
use Utopia\Detector\Detection\Runtime\Ruby;
use Utopia\Detector\Detection\Runtime\Swift;
use Utopia\Detector\Detector\Framework;
use Utopia\Detector\Detector\Packager;
use Utopia\Detector\Detector\Rendering;
use Utopia\Detector\Detector\Runtime;
use Utopia\Detector\Detector\Strategy;

class DetectorTest extends TestCase
{
    public function test_detect_packager(): void // TODO: Rename to testDetectPackager
    {
        foreach ($this->packagerDataProvider() as $data) {
            $detector = new Packager($data['files']);
            $detector
                ->addOption(new PNPM)
                ->addOption(new Yarn)
                ->addOption(new NPM);

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
                ->addOption(new CPP)
                ->addOption(new Dotnet);

            $runtime = $detector->detect();
            if ($runtime) {
                $this->assertEquals($data['runtime'], $runtime->getName());
                $this->assertEquals($data['commands'], $runtime->getCommands());
                $this->assertEquals($data['entrypoint'], $runtime->getEntrypoint());
            } else {
                $this->assertEquals($data['runtime'], null);
                $this->assertEquals($data['commands'], null);
                $this->assertEquals($data['entrypoint'], null);
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
                'entrypoint' => 'index.js',
            ],
            [
                'files' => ['package-lock.json', 'yarn.lock', 'tsconfig.json'],
                'runtime' => 'node',
                'commands' => 'yarn install && yarn build',
                'entrypoint' => 'index.js',
                'packager' => 'yarn',
            ],
            [
                'files' => ['composer.json', 'composer.lock'],
                'runtime' => 'php',
                'commands' => 'composer install && composer run build',
                'entrypoint' => 'index.php',
            ],
            [
                'files' => ['pubspec.yaml'],
                'runtime' => 'dart',
                'commands' => 'dart pub get',
                'entrypoint' => 'main.dart',
            ],
            [
                'files' => ['Gemfile', 'Gemfile.lock'],
                'runtime' => 'ruby',
                'commands' => 'bundle install && bundle exec rake build',
                'entrypoint' => 'main.rb',
            ],
            // test for FAILURE
            [
                'files' => ['index.html', 'style.css'],
                'runtime' => null,
                'commands' => null,
                'entrypoint' => null,
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
                ->addOption(new CPP)
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
                ->addOption(new CPP)
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

    public function test_framework_detection(): void
    {
        foreach ($this->frameworkDataProvider() as $data) {
            $detector = new Framework($data['files'], $data['packager'] ?? 'npm');
            $detector
                ->addOption(new Flutter)
                ->addOption(new NextJs)
                ->addOption(new Nuxt)
                ->addOption(new Astro)
                ->addOption(new Remix)
                ->addOption(new SvelteKit);

            $framework = $detector->detect();

            if ($framework) {
                $this->assertEquals($data['framework'], $framework->getName());
                $this->assertEquals($data['installCommand'], $framework->getInstallCommand());
                $this->assertEquals($data['buildCommand'], $framework->getBuildCommand());
                $this->assertEquals($data['outputDirectory'], $framework->getOutputDirectory());
            } else {
                $this->assertEquals($data['framework'], null);
            }
        }
    }

    public function frameworkDataProvider(): array
    {
        return [
            [
                'files' => ['src/main.js', '.gitignore', 'next.config.js', 'yarn.lock', 'index.html'],
                'framework' => 'Next.js',
                'installCommand' => 'yarn install',
                'buildCommand' => 'yarn build',
                'outputDirectory' => './.next',
                'packager' => 'yarn',
            ],
            [
                'files' => ['public', 'server', 'app.vue', 'nuxt.config.ts', 'package-lock.json', 'package.json', 'tsconfig.json'],
                'framework' => 'Nuxt',
                'installCommand' => 'npm install',
                'buildCommand' => 'npm run build',
                'outputDirectory' => './output',
            ],
            [
                'files' => ['public', 'src', 'astro.config.mjs', 'package-lock.json', 'package.json', 'tsconfig.json'],
                'framework' => 'Astro',
                'installCommand' => 'npm install',
                'buildCommand' => 'npm run build',
                'outputDirectory' => './dist',
            ],
            [
                'files' => ['app', 'public', 'remix.config.js', 'remix.env.d.ts', 'sandbox.config.js', 'tsconfig.json', 'package.json'],
                'framework' => 'Remix',
                'installCommand' => 'npm install',
                'buildCommand' => 'npm run build',
                'outputDirectory' => './build',
            ],
            [
                'files' => ['src', 'static', 'scripts', 'eslint.config.js', 'package.json', 'pnpm-lock.yaml', 'svelte.config.js', 'tsconfig.js', 'vite.config.js', 'vite.config.lib.js'],
                'framework' => 'SvelteKit',
                'installCommand' => 'npm install',
                'buildCommand' => 'npm run build',
                'outputDirectory' => './build',
            ],
            [
                'files' => ['src', 'types', 'makefile', 'components.js', 'debug.js', 'package.json', 'svelte.config.js'],
                'framework' => 'SvelteKit',
                'installCommand' => 'npm install',
                'buildCommand' => 'npm run build',
                'outputDirectory' => './build',
            ],
            [
                'files' => ['app', 'backend', 'public', 'Dockerfile', 'docker-compose.yml', 'ecosystem.config.js', 'middleware.ts', 'next.config.js', 'package-lock.json', 'package.json', 'server.js', 'tsconfig.json'],
                'framework' => 'Next.js',
                'installCommand' => 'npm install',
                'buildCommand' => 'npm run build',
                'outputDirectory' => './.next',
            ],
            [
                'files' => ['assets', 'components', 'layouts', 'pages', 'babel.config.js', 'error.vue', 'nuxt.config.js', 'yarn.lock'],
                'framework' => 'Nuxt',
                'installCommand' => 'npm install',
                'buildCommand' => 'npm run build',
                'outputDirectory' => './output',
            ],
            // test for FAILURE
            [
                'files' => ['index.html', 'style.css'],
                'framework' => null,
            ],
        ];
    }

    public function test_rendering_detection(): void
    {
        foreach ($this->renderingDataProvider() as $data) {
            $detector = new Rendering($data['files'], $data['framework']);
            $detector
                ->addOption(new SSR)
                ->addOption(new SSG);

            $rendering = $detector->detect();

            if ($rendering) {
                $this->assertEquals($data['rendering'], $rendering->getName());
            } else {
                $this->assertEquals($data['rendering'], null);
            }
        }
    }

    public function renderingDataProvider(): array
    {
        return [
            [
                'files' => ['server/pages/index.html', 'server/pages/api/users.js'],
                'framework' => 'Next.js',
                'rendering' => 'SSR',
            ],
            [
                'files' => ['server/pages/index.html', 'server/pages/api/users.js'],
                'framework' => 'Next.js',
                'rendering' => 'SSR',
            ],
            [
                'files' => ['index.html', 'about.html', '404.html'],
                'framework' => 'Next.js',
                'rendering' => 'SSG',
            ],
            [
                'files' => ['server/index.mjs', 'nitro.json'],
                'framework' => 'Nuxt',
                'rendering' => 'SSR',
            ],
            [
                'files' => ['index.html', '_nuxt/something.js'],
                'framework' => 'Nuxt',
                'rendering' => 'SSG',
            ],
            [
                'files' => ['server/pages/index.js', 'prerendered/about.html'],
                'framework' => 'SvelteKit',
                'rendering' => 'SSR',
            ],
            [
                'files' => ['index.html', 'about.html'],
                'framework' => 'SvelteKit',
                'rendering' => 'SSG',
            ],
            [
                'files' => ['index.html', 'style.css'],
                'framework' => 'Next.js',
                'rendering' => 'SSG',
            ],
        ];
    }
}

// TODO: Check for SPA detection
