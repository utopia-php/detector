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
    /**
     * @param string[] $files List of files to check
     * @dataProvider packagerDataProvider
     */
    public function testDetectPackager(array $files, ?string $expectedPackager): void
    {
        $detector = new Packager($files);
        $detector
            ->addOption(new PNPM())
            ->addOption(new Yarn())
            ->addOption(new NPM());

        $detectedPackager = $detector->detect();

        if ($expectedPackager) {
            $this->assertEquals($expectedPackager, $detectedPackager?->getName());
        } else {
            $this->assertNull($detectedPackager);
        }
    }

    /**
     * @return array<array{array<string>, string|null}>
    */
    public function packagerDataProvider(): array
    {
        return [
            [['bun.lockb', 'fly.toml', 'package.json', 'remix.config.js'], 'npm'],
            [['yarn.lock'], 'yarn'],
            [['pnpm-lock.yaml'], 'pnpm'],
            [['composer.json'], null],  // test for FAILURE
        ];
    }

    /**
     * @param string[] $files List of files to check
     * @dataProvider runtimeDataProviderByFilematch
     */
    public function testDetectRuntimeByFilematch(
        array $files,
        ?string $runtime,
        ?string $commands,
        ?string $entrypoint,
        string $packager = 'npm'
    ): void {
        $detector = new Runtime(
            $files,
            new Strategy(Strategy::FILEMATCH),
            $packager
        );

        $detector
            ->addOption(new Node())
            ->addOption(new Bun())
            ->addOption(new Deno())
            ->addOption(new PHP())
            ->addOption(new Python())
            ->addOption(new Dart())
            ->addOption(new Swift())
            ->addOption(new Ruby())
            ->addOption(new Java())
            ->addOption(new CPP())
            ->addOption(new Dotnet());

        $detectedRuntime = $detector->detect();

        if ($runtime) {
            $this->assertNotNull($detectedRuntime);
            $this->assertEquals($runtime, $detectedRuntime?->getName());
            $this->assertEquals($commands, $detectedRuntime?->getCommands());
            $this->assertEquals($entrypoint, $detectedRuntime?->getEntrypoint());
        } else {
            $this->assertNull($detectedRuntime);
        }
    }

    /**
    * @return array<array{array<string>, string|null, string|null, string|null, string|null}>
    */
    public function runtimeDataProviderByFilematch(): array
    {
        return [
            [['package-lock.json', 'yarn.lock', 'tsconfig.json'], 'node', 'npm install && npm run build', 'index.js', 'npm'],
            [['package-lock.json', 'yarn.lock', 'tsconfig.json'], 'node', 'yarn install && yarn build', 'index.js', 'yarn'],
            [['composer.json', 'composer.lock'], 'php', 'composer install && composer run build', 'index.php', 'npm'],
            [['pubspec.yaml'], 'dart', 'dart pub get', 'main.dart', 'npm'],
            [['Gemfile', 'Gemfile.lock'], 'ruby', 'bundle install && bundle exec rake build', 'main.rb', 'npm'],
            [['index.html', 'style.css'], null, null, null], // Test for FAILURE
        ];
    }

    /**
     * @param string[] $files List of files to check
     * @dataProvider runtimeDataProviderByLanguages
     */
    public function testDetectRuntimeByLanguage(
        array $files,
        ?string $runtime,
        ?string $commands,
        string $packager = 'npm'
    ): void {
        $detector = new Runtime(
            $files,
            new Strategy(Strategy::LANGUAGES),
            $packager
        );

        $detector
            ->addOption(new Node())
            ->addOption(new Bun())
            ->addOption(new Deno())
            ->addOption(new PHP())
            ->addOption(new Python())
            ->addOption(new Dart())
            ->addOption(new Swift())
            ->addOption(new Ruby())
            ->addOption(new Java())
            ->addOption(new CPP())
            ->addOption(new Dotnet());

        $detectedRuntime = $detector->detect();

        if ($runtime) {
            $this->assertNotNull($detectedRuntime);
            $this->assertEquals($runtime, $detectedRuntime?->getName());
            $this->assertEquals($commands, $detectedRuntime?->getCommands());
        } else {
            $this->assertNull($detectedRuntime);
        }
    }

    /**
     * @return array<array{array<string>, string|null, string|null, string|null}>
     */
    public function runtimeDataProviderByLanguages(): array
    {
        return [
            [
                ['TypeScript', 'JavaScript', 'DockerFile'],
                'node',
                'npm install && npm run build',
                'npm',
            ],
            [
                ['TypeScript', 'JavaScript', 'DockerFile'],
                'node',
                'yarn install && yarn build',
                'yarn',
            ],
            // Test for FAILURE
            [
                ['HTML'],
                null,
                null,
                'npm',
            ],
        ];
    }

    /**
     * @param string[] $files List of files to check
     * @dataProvider runtimeDataProviderByFileExtensions
     */
    public function testDetectRuntimeByFileExtension(
        array $files,
        ?string $runtime,
        ?string $commands,
        string $packager = 'npm'
    ): void {
        $detector = new Runtime(
            $files,
            new Strategy(Strategy::EXTENSION),
            $packager
        );

        $detector
            ->addOption(new Node())
            ->addOption(new Bun())
            ->addOption(new Deno())
            ->addOption(new PHP())
            ->addOption(new Python())
            ->addOption(new Dart())
            ->addOption(new Swift())
            ->addOption(new Ruby())
            ->addOption(new Java())
            ->addOption(new CPP())
            ->addOption(new Dotnet());

        $detectedRuntime = $detector->detect();

        if ($runtime) {
            $this->assertNotNull($detectedRuntime);
            $this->assertEquals($runtime, $detectedRuntime?->getName());
            $this->assertEquals($commands, $detectedRuntime?->getCommands());
        } else {
            $this->assertNull($detectedRuntime);
        }
    }

    /**
     * @return array<array{array<string>, string|null, string|null}>
     */
    public function runtimeDataProviderByFileExtensions(): array
    {
        return [
            [['main.ts', 'main.js', 'DockerFile'], 'node', 'npm install && npm run build'],
            [['main.ts', 'main.js', 'DockerFile'], 'node', 'yarn install && yarn build', 'yarn'],
            [['composer.json', 'index.php', 'DockerFile'], 'php', 'composer install && composer run build'],
            [['index.html', 'style.css'], null, null], // Test for FAILURE
        ];
    }

    /**
     * @param string[] $files List of files to check
     * @dataProvider frameworkDataProvider
     */
    public function testFrameworkDetection(array $files, ?string $framework, ?string $installCommand = null, ?string $buildCommand = null, ?string $outputDirectory = null, string $packager = 'npm'): void
    {
        $detector = new Framework($files, $packager);

        $detector
            ->addOption(new Flutter())
            ->addOption(new Nuxt())
            ->addOption(new Astro())
            ->addOption(new Remix())
            ->addOption(new SvelteKit())
            ->addOption(new NextJs());

        $detectedFramework = $detector->detect();

        if ($framework) {
            $this->assertNotNull($detectedFramework);
            $this->assertEquals($framework, $detectedFramework?->getName());
            $this->assertEquals($installCommand, $detectedFramework?->getInstallCommand());
            $this->assertEquals($buildCommand, $detectedFramework?->getBuildCommand());
            $this->assertEquals($outputDirectory, $detectedFramework?->getOutputDirectory());
        } else {
            $this->assertNull($detectedFramework);
        }
    }

    /**
     * @return array<array{array<string>, string|null, string|null, string|null, string|null}>
     */
    public function frameworkDataProvider(): array
    {
        return [
            [['src', 'types', 'makefile', 'components.js', 'debug.js', 'package.json', 'svelte.config.js'], 'sveltekit', 'npm install', 'npm run build', './build'],
            [['app', 'backend', 'public', 'Dockerfile', 'docker-compose.yml', 'ecosystem.config.js', 'middleware.ts', 'next.config.js', 'package-lock.json', 'package.json', 'server.js', 'tsconfig.json'], 'next.js', 'npm install', 'npm run build', './.next'],
            [['assets', 'components', 'layouts', 'pages', 'babel.config.js', 'error.vue', 'nuxt.config.js', 'yarn.lock'], 'nuxt', 'npm install', 'npm run build', './output'],
            [['app', 'public', 'remix.config.js', 'remix.env.d.ts', 'sandbox.config.js', 'tsconfig.json', 'package.json'], 'remix', 'npm install', 'npm run build', './build'],
            [['public', 'src', 'astro.config.mjs', 'package-lock.json', 'package.json', 'tsconfig.json'], 'astro', 'npm install', 'npm run build', './dist'],
            [['src', 'static', 'scripts', 'eslint.config.js', 'package.json', 'pnpm-lock.yaml', 'svelte.config.js', 'tsconfig.js', 'vite.config.js', 'vite.config.lib.js'], 'sveltekit', 'npm install', 'npm run build', './build'],
            [['index.html', 'style.css'], null, null, null], // Test for FAILURE
        ];
    }

    /**
     * @param string[] $files List of files to check
     * @dataProvider renderingDataProvider
     */
    public function testRenderingDetection(array $files, string $framework, string $rendering): void
    {
        $detector = new Rendering($files, $framework);
        $detector
            ->addOption(new SSR())
            ->addOption(new SSG());

        $detectedRendering = $detector->detect();

        if ($rendering) {
            $this->assertNotNull($detectedRendering);
            $this->assertEquals($rendering, $detectedRendering->getName());
        } else {
            $this->assertNull($detectedRendering);
        }
    }

    /**
     * @return array<array{array<string>, string, string}>
     */
    public function renderingDataProvider(): array
    {
        return [
            [['server/pages/index.html', 'server/pages/api/users.js', './.next/server/pages/_app.js'], 'next.js', 'ssr'],
            [['index.html', 'about.html', '404.html'], 'next.js', 'ssg'],
            [['nitro.json', './server/index.mjs'], 'nuxt', 'ssr'],
            [['index.html', '_nuxt/something.js'], 'nuxt', 'ssg'],
            [['server/pages/index.js', 'prerendered/about.html', './handler.js'], 'sveltekit', 'ssr'],
            [['index.html', 'about.html'], 'sveltekit', 'ssg'],
            [['index.html', 'style.css'], 'next.js', 'ssg'],
            [['./server/entry.mjs', './server/renderers.mjs', './server/pages/'], 'astro', 'ssr'],
            [['index.html', 'about.html'], 'astro', 'ssg'],
            [['./build/server/index.js', './build/server/renderers.js'], 'remix', 'ssr'],
            [['index.html', 'about.html'], 'remix', 'ssg'],
            [['index.html', 'style.css'], 'remix', 'ssg'],
            [['index.html', 'style.css'], 'flutter', 'ssg'],
        ];
    }
}
