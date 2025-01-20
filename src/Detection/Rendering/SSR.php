<?php

namespace Utopia\Detector\Detection\Rendering;

use Utopia\Detector\Detection\Rendering;

class SSR extends Rendering
{
    public function getName(): string
    {
        return 'ssr';
    }
}
