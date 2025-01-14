<?php

namespace Utopia\Detector\Detection\Models;

enum FrameworkType: string
{
    case ANGULAR = 'angular';
    case ASTRO = 'astro';
    case NEXTJS = 'nextjs';
    case NUXT = 'nuxt';
    case SVELTEKIT = 'sveltekit';
    case REMIX = 'remix';
    case FLUTTER = 'flutter';
    case OTHER = 'other';
}
