<?php

include_once 'vendor/autoload.php';

use Utopia\Detector\Adapter\Framework as FrameworkDetector;
use Utopia\Detector\Adapter\PackageManager as PackageManagerDetector;
use Utopia\Detector\Adapter\Rendering as RenderingDetector;
use Utopia\Detector\Adapter\Runtime as RuntimeDetector;
use Utopia\Detector\Detection\Models\FrameworkType;
use Utopia\Detector\Detection\Models\PackageManagerType;

$files = ['src/main.js', '.gitignore', 'package.json', 'yarn.lock'];

$packageManagerDetector = new PackageManagerDetector($files);
$detection = $packageManagerDetector->detect();
echo 'Detected package manager: '.$detection->getName()."\n";

$runtimeDetector = new RuntimeDetector($files, PackageManagerType::NPM);
$detection = $runtimeDetector->detect();
$installCommand = $detection->getCommand();
echo 'Detected runtime: '.$detection->getName()."\n";
echo 'Install command: '.$installCommand."\n";

$frameworkDetector = new FrameworkDetector($files, PackageManagerType::NPM);
$detection = $frameworkDetector->detect();
echo 'Detected framework: '.$detection->getName()."\n";
echo 'Install command: '.$detection->getInstallCommand()."\n";
echo 'Build command: '.$detection->getBuildCommand()."\n";

$renderingDetector = new RenderingDetector($files, FrameworkType::NEXTJS);
$detection = $renderingDetector->detect();
echo 'Detected rendering strategy: '.$detection->getName()."\n";
