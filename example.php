<?php

use Utopia\Detector\Framework;
use Utopia\Detector\Packager;
use Utopia\Detector\Rendering;
use Utopia\Detector\Runtime;

include_once 'vendor/autoload.php';

$files = ['src/main.js', '.gitignore', 'package.json', 'yarn.lock'];

/**
 * Function flows
 */

// 1. Detect NPM
$detector = new Packager($files);
$packager = $detector->detect();
echo 'Detected package manager: '.$packager->getName()."\n";

// 2. Detect Node
$detector = new Runtime($files, $packager->getName());
$runtime = $detector->detect();
echo 'Detected runtime: '.$runtime->getName()."\n";
echo 'Install command: '.$runtime->getCommand()."\n";

/**
 * Site flows
 */
echo "---\n";

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
