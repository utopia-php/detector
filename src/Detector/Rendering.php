<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Rendering as RenderingDetection;
use Utopia\Detector\Detection\Rendering\SSG;
use Utopia\Detector\Detection\Rendering\SSR;
use Utopia\Detector\Detector;

class Rendering extends Detector
{
    public function __construct(protected array $inputs, protected string $framework) {}

    private const FRAMEWORK_PATTERNS = [
        'Next.js' => [
            'ssr' => ['/server\/pages\/[^.]+\.html/', '/server\/pages\/api\/.*/'],
            'ssg' => ['/.*\.html/'],
        ],
        'Nuxt' => [
            'ssr' => ['/server\/.*/', '/nitro\.json/'],
            'ssg' => ['/.*\.html/'],
        ],
        'SvelteKit' => [
            'ssr' => ['/server\/.*/', '/prerendered\/.*/'],
            'ssg' => ['/.*\.html/'],
        ],
        'Astro' => [
            'ssr' => ['/_render-page\.js/', '/_middleware\.js/'],
            'ssg' => ['/.*\.html/'],
        ],
        'Remix' => [
            'ssr' => ['/server\/.*/'],
            'ssg' => ['/.*\.html/'],
        ],
        'Flutter' => [
            'ssg' => ['/index\.html/'],
        ],
    ];

    public function detect(): ?RenderingDetection
    {
        if (! isset(self::FRAMEWORK_PATTERNS[$this->framework])) {
            throw new \InvalidArgumentException("Unsupported framework: {$this->framework}");
        }

        $patterns = self::FRAMEWORK_PATTERNS[$this->framework];

        if (isset($patterns['ssr'])) {
            foreach ($patterns['ssr'] as $pattern) {
                foreach ($this->inputs as $input) {
                    if (preg_match($pattern, $input)) {
                        return new SSR;
                    }
                }
            }
        }

        if (isset($patterns['ssg'])) {
            foreach ($patterns['ssg'] as $pattern) {
                foreach ($this->inputs as $input) {
                    if (preg_match($pattern, $input)) {
                        return new SSG;
                    }
                }
            }
        }

        return null;
    }
}
