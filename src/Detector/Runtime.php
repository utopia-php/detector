<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Runtime as DetectionRuntime;
use Utopia\Detector\Detector;

class Strategy
{
    public const FILEMATCH = 'filematch';

    public const EXTENSION = 'extension';

    public const LANGUAGES = 'languages';

    private string $value;

    public function __construct(string $value)
    {
        if (! in_array($value, [self::FILEMATCH, self::EXTENSION, self::LANGUAGES])) {
            throw new \InvalidArgumentException("Invalid strategy: {$value}");
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

class Runtime extends Detector
{
    /**
     * @param  array<string>  $inputs
     */
    public function __construct(
        protected array $inputs,
        protected Strategy $strategy,
        protected string $packager = 'npm'
    ) {}

    public function detect(): ?DetectionRuntime
    {
        switch ($this->strategy->getValue()) {
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
                            $detector->setPackager($this->packager);

                            return $detector;
                        }
                    }
                }
                break;

            case Strategy::LANGUAGES:
                foreach ($this->options as $detector) {
                    foreach ($this->inputs as $language) {
                        if (\in_array($language, $detector->getLanguages())) {
                            $detector->setPackager($this->packager);

                            return $detector;
                        }
                    }
                }
                break;
        }

        return null;
    }
}
