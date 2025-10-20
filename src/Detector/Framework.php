<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Framework as FrameworkDetection;
use Utopia\Detector\Detector;

class Framework extends Detector
{
    /**
     * @var array<FrameworkDetection>
     */
    protected array $options = [];

    /**
     * @var array<string>
     */
    protected array $pathInputs = [];

    /**
     * @var array<string>
     */
    protected array $packageInputs = [];

    protected string $packager = 'npm';

    public function __construct(string $packager = 'npm')
    {
        parent::__construct();
        $this->packager = $packager;
    }

    /**
     * Add input with its type, only allowing 'path' and 'packages' types
     *
     * @param string $type Input type (must be 'path' or 'packages')
     * @param string $content Input content
     */
    public function addInput(string $type, string $content): self
    {
        if ($type !== 'path' && $type !== 'packages') {
            throw new \InvalidArgumentException("Framework detector only accepts 'path' and 'packages' input types, got '{$type}'");
        }

        parent::addInput($type, $content);

        if ($type === 'path') {
            $this->pathInputs[] = $content;
        } elseif ($type === 'packages') {
            $this->packageInputs[] = $content;
        }

        return $this;
    }

    /**
     * Performs framework detection based on input patterns.
     *
     * @return FrameworkDetection|null The detected framework or null if no match is found.
     */
    public function detect(): ?FrameworkDetection
    {
        foreach ($this->options as $detector) {
            $detectorFiles = $detector->getFiles();

            // Check path-based detection
            $matches = array_intersect($detectorFiles, $this->pathInputs);
            if (count($matches) > 0) {
                $detector->setPackager($this->packager);

                return $detector;
            }

            // Check package-based detection (for frameworks that support it)
            if (method_exists($detector, 'getPackages')) {
                $detectorPackages = $detector->getPackages();
                foreach ($this->packageInputs as $packageInput) {
                    foreach ($detectorPackages as $package) {
                        if (str_contains($packageInput, $package)) {
                            $detector->setPackager($this->packager);

                            return $detector;
                        }
                    }
                }
            }
        }

        return null;
    }
}
