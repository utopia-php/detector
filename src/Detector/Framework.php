<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Framework as FrameworkDetection;
use Utopia\Detector\Detection\Framework\NextJs;
use Utopia\Detector\Detector;

class Framework extends Detector
{
    public function __construct(protected array $inputs, protected string $packager)
    {
    }

    public function detect(): ?FrameworkDetection
    {
        // TODO: Implement detection using $this->files
        return new NextJs($this->packager);
    }
}
