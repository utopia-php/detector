<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Framework as FrameworkDetection;
use Utopia\Detector\Detector;

class Framework extends Detector
{
    public const INPUT_FILE = 'file';
    public const INPUT_PACKAGES = 'packages';

    /**
     * @var array<FrameworkDetection>
     */
    protected array $options = [];

    protected string $packager = 'npm';

    public function __construct(string $packager = 'npm')
    {
        parent::__construct();
        $this->packager = $packager;
    }

    public function addInput(string $content, string $type = ''): self
    {
        if ($type !== self::INPUT_FILE && $type !== self::INPUT_PACKAGES) {
            throw new \InvalidArgumentException("Invalid input type '{$type}'");
        }

        parent::addInput($content, $type);

        return $this;
    }

    /**
     * Performs framework detection based on input patterns.
     *
     * @return FrameworkDetection|null The detected framework or null if no match is found.
     */
    public function detect(): ?FrameworkDetection
    {
        $files = array_filter($this->inputs, fn ($input) => $input['type'] === self::INPUT_FILE);
        $packages = array_filter($this->inputs, fn ($input) => $input['type'] === self::INPUT_PACKAGES);

        foreach ($this->options as $detector) {
            // Check path-based detection
            $matches = array_intersect($detector->getFiles(), $files);
            if (count($matches) > 0) {
                $detector->setPackager($this->packager);
                return $detector;
            }

            // Check package-based detection
            $matches = array_intersect($detector->getPackages(), $packages);
            if (count($matches) > 0) {
                $detector->setPackager($this->packager);
                return $detector;
            }
        }

        return null;
    }
}
