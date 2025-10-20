<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\XStatic;
use Utopia\Detector\Detection\Rendering\SSR;
use Utopia\Detector\Detector;

class Rendering extends Detector
{
    /**
     * @var array<string>
     */
    protected array $pathInputs = [];

    protected string $framework;

    public function __construct(string $framework)
    {
        parent::__construct();
        $this->framework = $framework;
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

    public function detect(): RenderingDetection
    {
        $files = SSR::FRAMEWORK_FILES[$this->framework] ?? [];
        $matches = array_intersect($this->pathInputs, $files);

        if (count($matches) > 0) {
            return new SSR();
        }

        // set fallback file for Static if there is only one html file
        $htmlFiles = [];
        foreach ($this->pathInputs as $file) {
            if (\pathinfo($file, PATHINFO_EXTENSION) === 'html') {
                $htmlFiles[] = $file;
            }
        }

        if (\count($htmlFiles) === 1) {
            return new XStatic($htmlFiles[0]);
        } else {
            return new XStatic();
        }
    }
}
