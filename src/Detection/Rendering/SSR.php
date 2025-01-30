<?php

namespace Utopia\Detector\Detection\Rendering;

use Utopia\Detector\Detection\Rendering;

class SSR extends Rendering
{
    public const FRAMEWORK_FILES = [
        'Next.js' => ['./.next/server/pages/_app.js'],
        'Nuxt' => ['./server/index.mjs'],
        'SvelteKit' => ['./handler.js'],
        'Astro' => ['./server/entry.mjs'],
        'Remix' => ['./build/server/index.js'],
        'Flutter' => [],
        // TODO: Add Angular, Analog
    ];

    public function getName(): string
    {
        return 'SSR';
    }
}
