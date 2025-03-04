<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\XStatic;
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
            return new SSR(null);
        }

        // set fallback file for Static if there is only one html file
        $htmlFiles = array_filter($this->inputs, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'html';
        });

        if (count($htmlFiles) === 1) {
            return new XStatic($htmlFiles[0]);
        } else {
            return new XStatic(null);
        }
    }
}
