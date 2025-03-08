<?php

namespace Utopia\Detector\Detection\Rendering;

use Utopia\Detector\Detection\Rendering;

class SSR extends Rendering
{
    public const FRAMEWORK_FILES = [
        'nextjs' => ['.next/server/pages/_app.js'],
        'nuxt' => ['server/index.mjs'],
        'sveltekit' => ['handler.js'],
        'astro' => ['server/entry.mjs'],
        'remix' => ['build/server/index.js'],
        'flutter' => [],
    ];

    public function getName(): string
    {
        return 'ssr';
    }
}
