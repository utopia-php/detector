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
     * @param  array<string>  $inputs
     */
    public function __construct(protected array $inputs)
    {
    }

    public function detect(): ?PackagerDetection
    {
        foreach ($this->options as $packager) {
            $packagerFiles = $packager->getFiles();

            $matches = array_intersect($packagerFiles, $this->inputs);
            if (count($matches) > 0) {
                return $packager;
            }
        }

        return null;
    }
}
