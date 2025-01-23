<?php

use Utopia\Detector\Detection\Framework\Astro;
use Utopia\Detector\Detection\Framework\Flutter;
use Utopia\Detector\Detection\Framework\NextJs;
use Utopia\Detector\Detection\Framework\Nuxt;
use Utopia\Detector\Detection\Framework\Other;
use Utopia\Detector\Detection\Framework\Remix;
use Utopia\Detector\Detection\Framework\SvelteKit;
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
use Utopia\Detector\Detector\Framework;
use Utopia\Detector\Detector\Packager;
use Utopia\Detector\Detector\Rendering;
use Utopia\Detector\Detector\Runtime;
use Utopia\Detector\Strategy;

include_once 'vendor/autoload.php';

// $files = ['src/main.js', '.gitignore', 'package.json', 'yarn.lock'];
$files = ['bun.lockb', 'fly.toml', 'package.json', 'remix.config.js'];

/**
 * Function flows
 */

// 1. Detect NPM
$detector = new Packager($files);
$detector
    ->addOption(new Npm)
    ->addOption(new Pnpm)
    ->addOption(new Yarn);

$packager = $detector->detect();
echo 'Detected package manager: '.$packager->getName()."\n";

// 2. Detect Node
$files = ['TypeScript', 'JavaScript', 'DockerFile'];
$detector = new Runtime($files, Strategy::LANGUAGES, $packager->getName());
$detector
    ->addOption(new Bun)
    ->addOption(new Deno)
    ->addOption(new Node)
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
    echo 'Detected runtime: '.$runtime->getName()."\n";
    echo 'Install command: '.$runtime->getCommand()."\n";
}

/**
 * Site flows
 */
echo "---\n";

$files = ['src/main.js', '.gitignore', 'yarn.lock', 'index.html'];

// 1. Detect NPM
$detector = new Packager($files);
$detector
    ->addOption(new Npm)
    ->addOption(new Pnpm)
    ->addOption(new Yarn);
$packager = $detector->detect();
echo 'Detected package manager: '.$packager->getName()."\n";

// 2. Detect Next.js
$detector = new Framework($files, Strategy::EXTENSION, $packager->getName());
$detector
    ->addOption(new NextJs)
    ->addOption(new Nuxt)
    ->addOption(new Astro)
    ->addOption(new Flutter)
    ->addOption(new Remix)
    ->addOption(new SvelteKit)
    ->addOption(new Other);

$framework = $detector->detect();
if ($framework) {
    echo 'Detected framework: '.$framework->getName()."\n";
    echo 'Build command: '.$framework->getBuildCommand()."\n";
}

// 3. Detect SSR
if ($framework) {
    $detector = new Rendering($files, $framework->getName());
    $rendering = $detector->detect();
    echo 'Detected rendering strategy: '.$rendering->getName()."\n";
}
