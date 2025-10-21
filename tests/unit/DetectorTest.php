<?php

namespace Utopia\Tests;

use PHPUnit\Framework\TestCase;
use Utopia\Detector\Detection\Framework\Analog;
use Utopia\Detector\Detection\Framework\Angular;
use Utopia\Detector\Detection\Framework\Astro;
use Utopia\Detector\Detection\Framework\Flutter;
use Utopia\Detector\Detection\Framework\Lynx;
use Utopia\Detector\Detection\Framework\NextJs;
use Utopia\Detector\Detection\Framework\Nuxt;
use Utopia\Detector\Detection\Framework\React;
use Utopia\Detector\Detection\Framework\ReactNative;
use Utopia\Detector\Detection\Framework\Remix;
use Utopia\Detector\Detection\Framework\Svelte;
use Utopia\Detector\Detection\Framework\SvelteKit;
use Utopia\Detector\Detection\Framework\TanStackStart;
use Utopia\Detector\Detection\Framework\Vue;
use Utopia\Detector\Detection\Packager\NPM;
use Utopia\Detector\Detection\Packager\PNPM;
use Utopia\Detector\Detection\Packager\Yarn;
use Utopia\Detector\Detection\Rendering\XStatic;
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
        $detector = new Packager();
        $detector
            ->addOption(new PNPM())
            ->addOption(new Yarn())
            ->addOption(new NPM());

        foreach ($files as $file) {
            $detector->addInput($file);
        }

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

        foreach ($files as $file) {
            $detector->addInput($file);
        }

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
            [['index.html', 'style.css'], null, null, null, 'npm'], // Test for FAILURE
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

        foreach ($files as $file) {
            $detector->addInput($file);
        }

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

        foreach ($files as $file) {
            $detector->addInput($file);
        }

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
        $detector = new Framework($packager);

        $detector
            ->addOption(new Flutter())
            ->addOption(new Nuxt())
            ->addOption(new Astro())
            ->addOption(new Remix())
            ->addOption(new SvelteKit())
            ->addOption(new NextJs())
            ->addOption(new Lynx())
            ->addOption(new Angular())
            ->addOption(new Analog())
            ->addOption(new TanStackStart());

        foreach ($files as $file) {
            $detector->addInput($file, Framework::INPUT_FILE);
        }

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
            [['app', 'backend', 'public', 'Dockerfile', 'docker-compose.yml', 'ecosystem.config.js', 'middleware.ts', 'next.config.js', 'package-lock.json', 'package.json', 'server.js', 'tsconfig.json'], 'nextjs', 'npm install', 'npm run build', './.next'],
            [['assets', 'components', 'layouts', 'pages', 'babel.config.js', 'error.vue', 'nuxt.config.js', 'yarn.lock'], 'nuxt', 'npm install', 'npm run build', './output'],
            [['lynx.config.js'], 'lynx', 'npm install', 'npm run build', './dist'],
            [['src', 'package.json', 'tsconfig.json', 'angular.json', 'logo.png'], 'angular', 'npm install', 'npm run build', './dist/angular'],
            [['app', 'public', 'remix.config.js', 'remix.env.d.ts', 'sandbox.config.js', 'tsconfig.json', 'package.json'], 'remix', 'npm install', 'npm run build', './build'],
            [['public', 'src', 'astro.config.mjs', 'package-lock.json', 'package.json', 'tsconfig.json'], 'astro', 'npm install', 'npm run build', './dist'],
            [['src', 'static', 'scripts', 'eslint.config.js', 'package.json', 'pnpm-lock.yaml', 'svelte.config.js', 'tsconfig.js', 'vite.config.js', 'vite.config.lib.js'], 'sveltekit', 'npm install', 'npm run build', './build'],
            [['index.html', 'style.css'], null, null, null, null], // Test for FAILURE
        ];
    }

    /**
     * @param string[] $files List of files to check
     * @param string $framework The framework
     * @param string $rendering The expected rendering type
     * @param string|null $fallbackFile The expected fallback file
     * @dataProvider renderingDataProvider
     */
    public function testRenderingDetection(array $files, string $framework, string $rendering, ?string $fallbackFile): void
    {
        $detector = new Rendering($framework);
        $detector
            ->addOption(new SSR())
            ->addOption(new XStatic());

        foreach ($files as $file) {
            $detector->addInput($file);
        }

        $detectedRendering = $detector->detect();

        $this->assertNotNull($detectedRendering);
        $this->assertEquals($rendering, $detectedRendering->getName());
        $this->assertEquals($fallbackFile, $detectedRendering->getFallbackFile());
    }

    /**
     * @return array<array{array<string>, string, string|null, string|null}>
     */
    public function renderingDataProvider(): array
    {
        return [
            [['server/pages/index.html', 'server/pages/api/users.js', '.next/server/pages/_app.js'], 'nextjs', 'ssr', null],
            [['index.html', 'about.html', '404.html'], 'nextjs', 'static', null],
            [['nitro.json', 'server/index.mjs'], 'nuxt', 'ssr', null],
            [['server/server.mjs'], 'angular', 'ssr', null],
            [['server/index.mjs'], 'analog', 'ssr', null],
            [['server/index.mjs'], 'tanstack-start', 'ssr', null],
            [['index.html', '_nuxt/something.js'], 'nuxt', 'static', 'index.html'],
            [['server/pages/index.js', 'prerendered/about.html', 'handler.js'], 'sveltekit', 'ssr', null],
            [['index.html', 'about.html'], 'sveltekit', 'static', null],
            [['index.html', 'style.css'], 'nextjs', 'static', 'index.html'],
            [['server/entry.mjs', 'server/renderers.mjs', 'server/pages/'], 'astro', 'ssr', null],
            [['index.html', 'about.html'], 'astro', 'static', null],
            [['build/server/index.js', 'build/server/renderers.js'], 'remix', 'ssr', null],
            [['index.html', 'about.html'], 'remix', 'static', null],
            [['about.html', 'style.css'], 'remix', 'static', 'about.html'],
            [['index.html', 'style.css'], 'flutter', 'static', 'index.html'],
            [['index.html', 'about.html'], 'tanstack-start', 'static', null],
        ];
    }

    /**
     * Test TanStack Start framework detection with packages type input
     */
    public function testTanStackStartDetectionWithPackages(): void
    {
        $detector = new Framework('npm');

        $detector
            ->addOption(new Flutter())
            ->addOption(new Nuxt())
            ->addOption(new Astro())
            ->addOption(new Remix())
            ->addOption(new SvelteKit())
            ->addOption(new NextJs())
            ->addOption(new Lynx())
            ->addOption(new Angular())
            ->addOption(new Analog())
            ->addOption(new TanStackStart());

        $packageJson = json_encode([
            'name' => 'my-app',
            'dependencies' => [
                '@tanstack/react-start' => '^1.0.0',
                'react' => '^18.0.0',
            ],
        ], JSON_UNESCAPED_SLASHES) ?: '';

        $detector->addInput($packageJson, Framework::INPUT_PACKAGES);

        $detectedFramework = $detector->detect();

        $this->assertNotNull($detectedFramework);
        // Makes static code analyser smarter
        if (is_null($detectedFramework)) {
            throw new \Exception('Framework not detected');
        }
        $this->assertEquals('tanstack-start', $detectedFramework->getName());
        $this->assertEquals('npm install', $detectedFramework->getInstallCommand());
        $this->assertEquals('npm run build', $detectedFramework->getBuildCommand());
        $this->assertEquals('./dist', $detectedFramework->getOutputDirectory());
    }

    /**
     * Test TanStack Start framework detection with devDependencies
     */
    public function testTanStackStartDetectionWithDevPackages(): void
    {
        $detector = new Framework('pnpm');

        $detector->addOption(new TanStackStart());

        $packageJson = json_encode([
            'name' => 'my-app',
            'devDependencies' => [
                '@tanstack/react-start' => '^1.0.0',
            ],
        ], JSON_UNESCAPED_SLASHES) ?: '';

        $detector->addInput($packageJson, Framework::INPUT_PACKAGES);

        $detectedFramework = $detector->detect();

        $this->assertNotNull($detectedFramework);
        // Makes static code analyser smarter
        if (is_null($detectedFramework)) {
            throw new \Exception('Framework not detected');
        }

        $this->assertEquals('tanstack-start', $detectedFramework->getName());
        $this->assertEquals('pnpm install', $detectedFramework->getInstallCommand());
        $this->assertEquals('pnpm build', $detectedFramework->getBuildCommand());
    }

    /**
     * Test that Framework detector rejects invalid input types
     */
    public function testFrameworkDetectorRejectsInvalidInputType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid input type 'language'");

        $detector = new Framework('npm');
        $detector->addInput('JavaScript', 'language');
    }

    /**
     * @return array<mixed>
     */
    public function frameworkEdgeCasesProvider(): array
    {
        return [
            // React-based
            [
                'assertion' => 'Just react should mean just react',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2'
                    ]
                ]) ?: '',
                'framework' => 'react',
            ],
            [
                'assertion' => 'React with Next package is Next.js',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2',
                        'next' => '^12.0.7'
                    ]
                ]) ?: '',
                'framework' => 'nextjs',
            ],
            [
                'assertion' => 'React with Next config is Next.js',
                'files' => [
                    'package.json',
                    'next.config.js'
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2',
                    ]
                ]) ?: '',
                'framework' => 'nextjs',
            ],
            [
                'assertion' => 'React with React Native is React Native',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2',
                        'react-native' => '^0.68.2'
                    ]
                ]) ?: '',
                'framework' => 'react-native',
            ],
            [
                'assertion' => 'React with Tanstack Start is Tanstack Start',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2',
                        '@tanstack/react-start' => '^1.0.0'
                    ]
                ], JSON_UNESCAPED_SLASHES) ?: '',
                'framework' => 'tanstack-start',
            ],
            [
                'assertion' => 'React with Remix is Remix',
                'files' => [
                    'package.json',
                    'remix.config.js'
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2'
                    ]
                ]) ?: '',
                'framework' => 'remix',
            ],
            [
                'assertion' => 'React with Lynx config file is Lynx',
                'files' => [
                    'package.json',
                    'lynx.config.ts'
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2'
                    ]
                ]) ?: '',
                'framework' => 'lynx',
            ],
            [
                'assertion' => 'React with Lynx package is Lynx',
                'files' => [
                    'package.json'
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'react' => '^17.0.2',
                        '@lynx-js/react' => '^1.0.0'
                    ]
                ], JSON_UNESCAPED_SLASHES) ?: '',
                'framework' => 'lynx',
            ],

            // Angular-based
            [
                'assertion' => 'Just Angular should mean just Angular',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        '@angular/core' => '^14.0.0'
                    ]
                ]) ?: '',
                'framework' => 'angular',
            ],
            [
                'assertion' => 'Angular with Analog is Analog',
                'files' => [
                    'package.json',
                    'angular.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        '@angular/core' => '^14.0.0',
                        '@analogjs/platform' => '^14.0.0',
                    ]
                ], JSON_UNESCAPED_SLASHES) ?: '',
                'framework' => 'analog',
            ],

            // Vue-based
            [
                'assertion' => 'Just Vue should mean just Vue',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'vue' => '^3.2.47',
                    ]
                ]) ?: '',
                'framework' => 'vue',
            ],
            [
                'assertion' => 'Vue with Nuxt config file is Nuxt',
                'files' => [
                    'package.json',
                    'nuxt.config.js',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'vue' => '^3.2.47',
                    ]
                ]) ?: '',
                'framework' => 'nuxt',
            ],
            [
                'assertion' => 'Vue with Nuxt package is Nuxt',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'vue' => '^3.2.47',
                        'nuxt' => '^3.0.0'
                    ]
                ]) ?: '',
                'framework' => 'nuxt',
            ],

            // Astro-based
            [
                'assertion' => 'Just Astro should mean just Astro',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'astro' => '^5.0.0'
                    ]
                ]) ?: '',
                'framework' => 'astro',
            ],
            [
                'assertion' => 'Astro with React is Astro',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'astro' => '^5.0.0',
                        'react' => '^18.2.0'
                    ]
                ]) ?: '',
                'framework' => 'astro',
            ],
            [
                'assertion' => 'Astro with Angular package is Astro',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'astro' => '^5.0.0',
                        '@angular/core' => '^18.2.0'
                    ]
                ], JSON_UNESCAPED_SLASHES) ?: '',
                'framework' => 'astro',
            ],
            [
                'assertion' => 'Astro with Angular file is Astro',
                'files' => [
                    'package.json',
                    'angular.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'astro' => '^5.0.0',
                    ]
                ], JSON_UNESCAPED_SLASHES) ?: '',
                'framework' => 'astro',
            ],
            [
                'assertion' => 'Astro with Angular file and package is Astro',
                'files' => [
                    'package.json',
                    'angular.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'astro' => '^5.0.0',
                        'angular' => '^18.2.0'
                    ]
                ], JSON_UNESCAPED_SLASHES) ?: '',
                'framework' => 'astro',
            ],
            [
                'assertion' => 'Astro with Vue is Astro',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'astro' => '^5.0.0',
                        'vue' => '^3.2.47'
                    ]
                ]) ?: '',
                'framework' => 'astro',
            ],


            // Svelte-based
            [
                'assertion' => 'Just Svelte should mean just Svelte',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'svelte' => '^3.54.0'
                    ]
                ]) ?: '',
                'framework' => 'svelte',
            ],
            [
                'assertion' => 'Svelte with SvelteKit is SvelteKit',
                'files' => [
                    'package.json',
                ],
                'package' => \json_encode([
                    'dependencies' => [
                        'svelte' => '^3.54.0',
                        '@sveltejs/kit' => '^1.0.0'
                    ]
                ], JSON_UNESCAPED_SLASHES) ?: '',
                'framework' => 'sveltekit',
            ],
        ];
    }

    /**
     * Test scenarios that can possibly result in multiple frameworks,
     * but only one is accurate detection.
     * @param array<string> $files
     * @dataProvider frameworkEdgeCasesProvider
     */
    public function testFrameworkEdgeCases(string $assertion, array $files, string $packageFile, string $framework): void
    {
        $detector = new Framework('npm');

        $detector
            ->addOption(new Analog())
            ->addOption(new Angular())
            ->addOption(new Astro())
            ->addOption(new Flutter())
            ->addOption(new Lynx())
            ->addOption(new NextJs())
            ->addOption(new Nuxt())
            ->addOption(new React())
            ->addOption(new ReactNative())
            ->addOption(new Remix())
            ->addOption(new Svelte())
            ->addOption(new SvelteKit())
            ->addOption(new TanStackStart())
            ->addOption(new Vue());

        foreach ($files as $file) {
            $detector->addInput($file, Framework::INPUT_FILE);
        }

        $detector->addInput($packageFile, Framework::INPUT_PACKAGES);

        $detection = $detector->detect();

        $this->assertNotNull($detection, $assertion);
        // Makes static code analyser smarter
        if (is_null($detection)) {
            throw new \Exception('Framework not detected');
        }

        $this->assertEquals($framework, $detection->getName(), $assertion);
    }
}
