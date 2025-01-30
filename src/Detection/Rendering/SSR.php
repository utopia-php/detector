<?php

namespace Utopia\Detector\Detection\Rendering;

use Utopia\Detector\Detection\Rendering;

class SSR extends Rendering
{
    public const FRAMEWORK_FILES = [
        'Next.js' => ['server/pages/', 'server/app/'],
        'Nuxt' => ['server/'],
        'SvelteKit' => [
            'src/hooks.server.js', 'src/hooks.server.ts',
            'src/routes/+page.server.js', 'src/routes/+page.server.ts',
            'build/server/', 'server/',
        ],
        'Astro' => ['server/', 'server.mjs'],
        'Remix' => ['server.js', 'server.ts', 'entry.server.tsx', 'entry.server.js', 'index.js', 'server/'],
        'Flutter' => [],
        // TODO: Add Angular, Analog
    ];

    public function getName(): string
    {
        return 'SSR';
    }
}
