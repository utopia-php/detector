<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\SSG;
use Utopia\Detector\Detection\Rendering\SSR;
use Utopia\Detector\Detector;

class Rendering extends Detector
{
    private const FRAMEWORK_SSR_FILES = [
        'Next.js' => ['server/pages/', 'server/app/'],
        'Nuxt' => ['.output/server/', 'server/'],
        'SvelteKit' => [
            'src/hooks.server.js', 'src/hooks.server.ts',
            'src/routes/+page.server.js', 'src/routes/+page.server.ts',
            'build/server/', 'server/',
        ],
        'Astro' => ['server/', 'server.mjs'],
        'Remix' => ['server.js', 'server.ts', 'entry.server.tsx', 'entry.server.js', 'index.js', 'server/'],
        'Flutter' => [],
    ];

    public function __construct(protected array $inputs, protected string $framework) {}

    public function detect(): RenderingDetection
    {
        if (! isset(self::FRAMEWORK_SSR_FILES[$this->framework])) {
            throw new \InvalidArgumentException("Unsupported framework: {$this->framework}");
        }

        foreach ($this->inputs as $input) {
            foreach (self::FRAMEWORK_SSR_FILES[$this->framework] as $ssrPath) {
                if (str_contains($input, $ssrPath)) {
                    return new SSR;
                }
            }
        }

        return new SSG;
    }
}
