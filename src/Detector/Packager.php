<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Packager as PackagerDetection;
use Utopia\Detector\Detector;

class Packager extends Detector
{
    /**
     * @var array<PackagerDetection>
     */
    protected array $options = [];

    /**
     * @var array<string>
     */
    protected array $pathInputs = [];

    public function __construct()
    {
        parent::__construct();
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
        }

        return $this;
    }

    public function detect(): ?PackagerDetection
    {
        foreach ($this->options as $packager) {
            $packagerFiles = $packager->getFiles();

            $matches = array_intersect($packagerFiles, $this->pathInputs);
            if (count($matches) > 0) {
                return $packager;
            }
        }

        return null;
    }
}
