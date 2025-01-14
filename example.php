<?php

use Utopia\Detector\Detector;
use Utopia\Detector\Adapter\Framework;
use Utopia\Detector\Adapter\PackageManager;
use Utopia\Detector\Adapter\RenderingStrategy;
use Utopia\Detector\Adapter\Runtime;
use Utopia\Detector\Detection\Framework\Detection as FrameworkDetection;
use Utopia\Detector\Detection\Models\FrameworkType;
use Utopia\Detector\Detection\Models\PackageManagerType;
use Utopia\Detector\Detection\PackageManager\Detection as PackageManagerDetection;
use Utopia\Detector\Detection\RenderingStrategy\Detection as RenderingStrategyDetection;
use Utopia\Detector\Detection\Runtime\Detection as RuntimeDetection;

$files = ['src/main.js', '.gitignore', 'package.json', 'yarn.lock'];

$packageManagerDetector = new Detector($files, new PackageManager());
$detection = $packageManagerDetector->detect();

if ($detection instanceof PackageManagerDetection) {
    echo "Detected package manager: " . $detection->getName() . "\n";
} else {
    echo "No package manager detected.\n";
}

$runtimeDetector = new Detector($files, new Runtime($files, PackageManagerType::NPM));
$detection = $runtimeDetector->detect();

if ($detection instanceof RuntimeDetection) {
    $installCommand = $detection->getCommand();
    echo "Detected runtime: " . $detection->getName() . "\n";
    echo "Install command: " . $installCommand . "\n";
} else {
    echo "No runtime detected.\n";
}

$frameworkDetector = new Detector($files, new Framework($files, PackageManagerType::NPM));
$detection = $frameworkDetector->detect();

if ($detection instanceof FrameworkDetection) {
    echo "Detected framework: " . $detection->getName() . "\n";
    echo "Install command: " . $detection->getInstallCommand() . "\n";
    echo "Build command: " . $detection->getBuildCommand() . "\n";
} else {
    echo "No framework detected.\n";
}

$renderingStrategyDetector = new Detector($files, new RenderingStrategy(FrameworkType::NEXTJS));
$detection = $renderingStrategyDetector->detect();

if ($detection instanceof RenderingStrategyDetection) {
    echo "Detected rendering strategy: " . $detection->getName() . "\n";
} else {
    echo "No rendering strategy detected.\n";
}
