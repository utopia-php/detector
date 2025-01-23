<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Runtime as DetectionRuntime;
use Utopia\Detector\Detector;
use Utopia\Detector\Strategy;

class Runtime extends Detector
{
    public function __construct(
        protected array $inputs,
        protected Strategy $strategy,
        protected string $packager = 'npm'
    ) {}

    public function detect(): ?DetectionRuntime
    {
        switch ($this->strategy) {
            case Strategy::FILEMATCH:
                foreach ($this->options as $detector) {
                    $detectorFiles = $detector->getFiles();

                    $matches = \array_intersect($detectorFiles, $this->inputs);
                    if (\count($matches) > 0) {
                        $detector->setPackager($this->packager); // TODO: Fix it

                        return $detector;
                    }
                }
                break;

            case Strategy::EXTENSION:
                foreach ($this->options as $detector) {
                    foreach ($this->inputs as $file) {
                        if (\in_array(pathinfo($file, PATHINFO_EXTENSION), $detector->getFileExtensions())) {
                            return $detector;
                        }
                    }
                }
                break;

            case Strategy::LANGUAGES:
                foreach ($this->options as $detector) {
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
