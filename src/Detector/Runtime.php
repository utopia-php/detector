<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Runtime as RuntimeDetection;
use Utopia\Detector\Detector;

class Runtime extends Detector
{
    /**
     * @var array<RuntimeDetection>
     */
    protected array $options = [];

    /**
     * @param  array<string>  $inputs
     */
    public function __construct(
        protected array $inputs,
        protected Strategy $strategy,
        protected string $packager = 'npm'
    ) {
    }

    public function detect(): ?RuntimeDetection
    {
        switch ($this->strategy->getValue()) {
            case Strategy::FILEMATCH:
                foreach ($this->options as $detector) {
                    $detectorFiles = $detector->getFiles();
                    $matches = array_intersect($detectorFiles, $this->inputs);
                    if (count($matches) > 0) {
                        $detector->setPackager($this->packager);

                        return $detector;
                    }
                }
                break;

            case Strategy::EXTENSION:
                foreach ($this->options as $detector) {
                    $detectorExtensions = $detector->getFileExtensions();
                    $matches = array_intersect($detectorExtensions, array_map(function ($file) {
                        return pathinfo($file, PATHINFO_EXTENSION);
                    }, $this->inputs));
                    if (count($matches) > 0) {
                        $detector->setPackager($this->packager);

                        return $detector;
                    }
                }
                break;

            case Strategy::LANGUAGES:
                foreach ($this->options as $detector) {
                    $detectorLanguages = $detector->getLanguages();
                    $matches = array_intersect($detectorLanguages, $this->inputs);
                    if (count($matches) > 0) {
                        $detector->setPackager($this->packager);

                        return $detector;
                    }
                }
                break;
        }

        return null;
    }
}
