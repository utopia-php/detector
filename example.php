<?php

use Utopia\Detector\Detection\Framework\Astro;
use Utopia\Detector\Detection\Framework\Flutter;
use Utopia\Detector\Detection\Framework\NextJs;
use Utopia\Detector\Detection\Framework\Nuxt;
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
use Utopia\Detector\Detector\Strategy;

include_once 'vendor/autoload.php';

// $files = ['src/main.js', '.gitignore', 'package.json', 'yarn.lock'];
$files = ['bun.lockb', 'fly.toml', 'package.json', 'remix.config.js'];

/**
 * Function flows
 */

// 1. Detect NPM
$detector = new Packager($files);
$detector
    ->addOption(new Pnpm)
    ->addOption(new Yarn)
    ->addOption(new Npm);

$packager = $detector->detect();
echo 'Detected package manager: '.$packager->getName()."\n";

// 2. Detect Node
$files = ['TypeScript', 'JavaScript', 'DockerFile'];
$detector = new Runtime($files, new Strategy(Strategy::LANGUAGES), $packager->getName());
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
    echo 'Install command: '.$runtime->getCommands()."\n";
}

/**
 * Site flows
 */
echo "---\n";

// $files = ['src/main.js', '.gitignore', 'yarn.lock', 'index.html'];
// $files = ['public', 'server', 'app.vue', 'nuxt.config.ts', 'package-lock.json', 'package.json', 'tsconfig.json']; // nuxt
// $files = ['public', 'src', 'astro.config.mjs', 'package-lock.json', 'package.json', 'tsconfig.json']; // astro
// $files = ['app', 'public', 'remix.config.js', 'remix.env.d.ts', 'sandbox.config.js', 'tsconfig.json', 'package.json']; //remix
// $files = ['src', 'static', 'scripts', 'eslint.config.js', 'package.json', 'pnpm-lock.yaml', 'svelte.config.js', 'tsconfig.js', 'vite.config.js', 'vite.config.lib.js']; // sveltekit
// $files = ['src', 'types', 'makefile', 'components.js', 'debug.js', 'package.json']; //sveltekit
// $files = ['app', 'backend', 'public', 'Dockerfile', 'docker-compose.yml', 'ecosystem.config.js', 'middleware.ts', 'next.config.js', 'package-lock.json', 'package.json', 'server.js', 'tsconfig.json']; // nextjs
$files = ['assets', 'components', 'layouts', 'pages', 'babel.config.js', 'error.vue', 'nuxt.config.js', 'yarn.lock']; // nuxt

// 1. Detect NPM
$detector = new Packager($files);
$detector
    ->addOption(new Npm)
    ->addOption(new Pnpm)
    ->addOption(new Yarn);
$packager = $detector->detect();
echo 'Detected package manager: '.$packager->getName()."\n";

// 2. Detect Next.js
$detector = new Framework($files, $packager->getName());
$detector
    ->addOption(new Flutter)
    ->addOption(new NextJs)
    ->addOption(new Nuxt)
    ->addOption(new Astro)
    ->addOption(new Remix)
    ->addOption(new SvelteKit);

$framework = $detector->detect();
if ($framework) {
    echo 'Detected framework: '.$framework->getName()."\n";
    echo 'Build command: '.$framework->getBuildCommand()."\n";
} else {
    echo 'No framework detected'."\n";
}

// 3. Detect SSR
if ($framework) {
    // $files = [
    //     'server/pages/index.html',
    //     'server/pages/api/users.js'
    // ]; //next.js SSR
    // $files = [
    //     'index.html',
    //     'about.html',
    //     '404.html'
    // ]; //next.js SSG
    // $files = [
    //     'server/index.mjs',
    //     'nitro.json'
    // ]; //nuxt SSR
    // $files = [
    //     'index.html',
    //     '_nuxt/something.js'
    // ]; //SSG
    // $files = [
    //     'server/pages/index.js',
    //     'prerendered/about.html'
    // ]; // SvelteKit SSR
    // $files = [
    //     'index.html',
    //     'about.html'
    // ]; // SvelteKit SSG
    // $files = [
    //     '_render-page.js',
    //     '_middleware.js'
    // ]; // Astro SSR
    // $files = [
    //     'index.html',
    //     'about.html'
    // ]; // Astro SSG
    // $files = [
    //     'server/index.js',
    //     'server/routes/about.js'
    // ]; // Remix SSR
    $files = [
        'index.html',
    ]; // Flutter SSG
    $detector = new Rendering($files, $framework->getName());
    $rendering = $detector->detect();
    if ($rendering) {
        echo 'Detected rendering strategy: '.$rendering->getName()."\n";
    } else {
        echo 'No rendering strategy detected';
    }
}
