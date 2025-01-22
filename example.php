<?php

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
$packager = $detector->detect();
echo 'Detected package manager: '.$packager->getName()."\n";

// 2. Detect Node
$files = ['TypeScript', 'JavaScript', 'DockerFile'];
$detector = new Runtime($files, $packager->getName(), Strategy::LANGUAGES);
$detector
    ->addDetector(new Deno($packager->getName())) // TODO: How can we avoid passing the packager name here?
    ->addDetector(new Bun($packager->getName()))
    ->addDetector(new Node($packager->getName()))
    ->addDetector(new PHP($packager->getName()))
    ->addDetector(new Python($packager->getName()))
    ->addDetector(new Dart($packager->getName()))
    ->addDetector(new Swift($packager->getName()))
    ->addDetector(new Ruby($packager->getName()))
    ->addDetector(new Java($packager->getName()))
    ->addDetector(new Cpp($packager->getName()))
    ->addDetector(new Dotnet($packager->getName()));

$runtime = $detector->detect();
if ($runtime) {
    echo 'Detected runtime: '.$runtime->getName()."\n";
    echo 'Install command: '.$runtime->getCommand()."\n";
}

/**
 * Site flows
 */
echo "---\n";

$files = ['src/main.js', '.gitignore', 'package.json', 'yarn.lock'];

// 1. Detect NPM
$detector = new Packager($files);
$packager = $detector->detect();
echo 'Detected package manager: '.$packager->getName()."\n";

// 2. Detect Next.js
$detector = new Framework($files, $packager->getName());
$framework = $detector->detect();
echo 'Detected framework: '.$framework->getName()."\n";
echo 'Build command: '.$framework->getBuildCommand()."\n";

// 3. Detect SSR
$detector = new Rendering($files, $framework->getName());
$rendering = $detector->detect();
echo 'Detected rendering strategy: '.$rendering->getName()."\n";
