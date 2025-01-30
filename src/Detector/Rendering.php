<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\SSG;
use Utopia\Detector\Detection\Rendering\SSR;
use Utopia\Detector\Detector;

class Rendering extends Detector
{
    public function __construct(protected array $inputs, protected string $framework) {}

    public function detect(): RenderingDetection
    {
        if (! isset(SSR::FRAMEWORK_FILES[$this->framework])) {
            throw new \InvalidArgumentException("Unsupported framework: {$this->framework}");
        }

        foreach ($this->inputs as $input) {
            foreach (SSR::FRAMEWORK_FILES[$this->framework] as $ssrPath) {
                if (str_contains($input, $ssrPath)) {
                    return new SSR;
                }
            }
        }

        return new SSG;
    }
}
