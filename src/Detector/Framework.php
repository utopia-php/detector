<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Framework as FrameworkDetection;
use Utopia\Detector\Detector;

enum Strategy: string
{
    case FILEMATCH = 'filematch';
    case EXTENSION = 'extension';
    case LANGUAGES = 'languages';
}

class Framework extends Detector
{
    public function __construct(
        protected array $inputs,
        protected string $packager = 'npm', // TODO: Check if it is okay to mark as default here
        protected Strategy $strategy = Strategy::FILEMATCH
    ) {}

    public function detect(): ?FrameworkDetection
    {
        switch ($this->strategy) {
            case Strategy::FILEMATCH:
                break;
            case Strategy::EXTENSION:
                break;
            case Strategy::LANGUAGES:
                break;
        }

        return null;
    }
}
