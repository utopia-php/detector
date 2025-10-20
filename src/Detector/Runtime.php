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
     * @var array<string>
     */
    protected array $pathInputs = [];

    /**
     * @var array<string>
     */
    protected array $extensionInputs = [];

    /**
     * @var array<string>
     */
    protected array $languageInputs = [];

    protected Strategy $strategy;

    protected string $packager = 'npm';

    public function __construct(Strategy $strategy, string $packager = 'npm')
    {
        parent::__construct();
        $this->strategy = $strategy;
        $this->packager = $packager;
    }

    /**
     * Add input with its type
     *
     * @param string $type Input type
     * @param string $content Input content
     */
    public function addInput(string $type, string $content): self
    {
        parent::addInput($type, $content);

        if ($type === 'path') {
            $this->pathInputs[] = $content;
        } elseif ($type === 'extension') {
            $this->extensionInputs[] = $content;
        } elseif ($type === 'language') {
            $this->languageInputs[] = $content;
        }

        return $this;
    }

    public function detect(): ?RuntimeDetection
    {
        switch ($this->strategy->getValue()) {
            case Strategy::FILEMATCH:
                foreach ($this->options as $detector) {
                    $detectorFiles = $detector->getFiles();
                    $matches = array_intersect($detectorFiles, $this->pathInputs);
                    if (count($matches) > 0) {
                        $detector->setPackager($this->packager);

                        return $detector;
                    }
                }
                break;

            case Strategy::EXTENSION:
                foreach ($this->options as $detector) {
                    $detectorExtensions = $detector->getFileExtensions();
                    $inputExtensions = array_map(function ($file) {
                        return pathinfo($file, PATHINFO_EXTENSION);
                    }, $this->pathInputs);
                    $matches = array_intersect($detectorExtensions, $inputExtensions);
                    if (count($matches) > 0) {
                        $detector->setPackager($this->packager);

                        return $detector;
                    }
                }
                break;

            case Strategy::LANGUAGES:
                foreach ($this->options as $detector) {
                    $detectorLanguages = $detector->getLanguages();
                    $matches = array_intersect($detectorLanguages, $this->languageInputs);
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
