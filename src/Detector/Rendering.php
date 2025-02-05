<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\SSG;
use Utopia\Detector\Detection\Rendering\SSR;
use Utopia\Detector\Detector;

class Rendering extends Detector
{
    /**
     * @param  array<string>  $inputs
     */
    public function __construct(protected array $inputs, protected string $framework)
    {
    }

    public function detect(): RenderingDetection
    {
        if (! isset(SSR::FRAMEWORK_FILES[$this->framework])) {
            throw new \Exception("Unsupported framework: {$this->framework}");
        }

        $matches = array_intersect($this->inputs, SSR::FRAMEWORK_FILES[$this->framework]);
        if (count($matches) > 0) {
            return new SSR;
        }

        return new SSG;
    }
}
