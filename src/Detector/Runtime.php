<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Runtime as DetectionRuntime;
use Utopia\Detector\Detector;

enum Strategy: string
{
    case FILEMATCH = 'filematch';
    case EXTENSION = 'extension';
    case LANGUAGES = 'languages';
}

class Runtime extends Detector
{
    public function __construct(
        protected array $inputs,
        protected string $packager = 'npm',
        protected Strategy $strategy = Strategy::FILEMATCH
    ) {}

    public function detect(): ?DetectionRuntime
    {
        switch ($this->strategy) {
            case Strategy::FILEMATCH:
                foreach ($this->detectors as $detector) {
                    $detectorFiles = $detector->getFiles();

                    $matches = \array_intersect($detectorFiles, $this->inputs);
                    if (\count($matches) > 0) {
                        return $detector;
                    }
                }
                break;

            case Strategy::EXTENSION:
                foreach ($this->detectors as $detector) {
                    foreach ($this->inputs as $file) {
                        if (\in_array(pathinfo($file, PATHINFO_EXTENSION), $detector->getFileExtensions())) {
                            return $detector;
                        }
                    }
                }
                break;

            case Strategy::LANGUAGES:
                foreach ($this->detectors as $detector) {
                    foreach ($this->inputs as $language) {
                        if (\in_array($language, $detector->getLanguages())) {
                            return $detector;
                        }
                    }
                }
                break;
        }

        return null;
    }
}
