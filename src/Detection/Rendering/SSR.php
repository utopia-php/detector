<?php

namespace Utopia\Detector\Detection\Rendering;

use Utopia\Detector\Detection\Rendering;

class SSR extends Rendering
{
    private const FRAMEWORK_FILES = [
        'nextjs' => ['.next/server/webpack-runtime.js', '.next/turbopack'],
        'nuxt' => ['server/index.mjs'],
        'sveltekit' => ['handler.js'],
        'astro' => ['server/entry.mjs'],
        'remix' => ['build/server/index.js'],
        'angular' => ['server/server.mjs'],
        'analog' => ['server/index.mjs'],
        'tanstack-start' => ['server/server.js', 'server/index.mjs'],
        'flutter' => [],
        'lynx' => [],
    ];

    /**
     * @return array<string>
     */
    public function getFiles(string $framework): array
    {
        return self::FRAMEWORK_FILES[$framework] ?? [];
    }

    public function getName(): string
    {
        return 'ssr';
    }
}
