<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Framework as FrameworkDetection;
use Utopia\Detector\Detector;

class Framework extends Detector
{
    /**
     * @param  array<string>  $inputs
     */
    public function __construct(
        protected array $inputs,
        protected string $packager = 'npm'
    ) {}

    /**
     * Performs framework detection based on input patterns.
     *
     * @return FrameworkDetection|null The detected framework or null if no match is found.
     */
    public function detect(): ?FrameworkDetection
    {
        foreach ($this->options as $detector) {
            $detectorFiles = $detector->getFiles();

            foreach ($this->inputs as $input) {
                foreach ($detectorFiles as $pattern) {
                    if ($input === $pattern) {
                        $detector->setPackager($this->packager);

                        return $detector;
                    }
                }
            }
        }

        return null;
    }
}
