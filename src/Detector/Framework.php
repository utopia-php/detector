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
        $files = array_map(fn ($input) => $input['content'], $files);

        $packages = array_filter($this->inputs, fn ($input) => $input['type'] === self::INPUT_PACKAGES);
        $packages = array_map(fn ($input) => $input['content'], $packages);

        // List of possible frameworks with count of matches
        $frameworks = [];
        foreach ($this->options as $detector) {
            $frameworks[$detector->getName()] = 0;
        }

        foreach ($this->options as $detector) {
            // Check package-based detection
            foreach ($packages as $packageJson) {
                foreach ($detector->getPackages() as $packageNeeded) {
                    if (str_contains($packageJson, '"'.$packageNeeded.'"')) {
                        $frameworks[$detector->getName()] += 1;
                    }
                }
            }

            // Check path-based detection
            $matches = array_intersect($detector->getFiles(), $files);
            $frameworks[$detector->getName()] += \count($matches);
        }

        // Filter out frameworks without matches
        $frameworks = array_filter($frameworks, fn ($count) => $count > 0);

        // Sort for framework with most matches to be first
        arsort($frameworks);

        if (\count($frameworks) > 0) {
            foreach ($this->options as $detector) {
                if ($detector->getName() === \array_key_first($frameworks)) {
                    $detector->setPackager($this->packager);
                    return $detector;
                }
            }
        }

        return null;
    }
}
