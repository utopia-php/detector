<?php

namespace Utopia\Detector\Detection\Models;

enum RuntimeType: string
{
    case NODE = 'node';
    case PHP = 'php';
    case PYTHON = 'python';
    case PYTHON_ML = 'python-ml';
    case DENO = 'deno';
    case RUBY = 'ruby';
    case JAVA = 'java';
    case GO = 'go';
    case RUST = 'rust';
    case BUN = 'bun';
    case DART = 'dart';
    case SWIFT = 'swift';
    case KOTLIN = 'kotlin';
    case DOTNET = 'dotnet';
    case CPP = 'cpp';
    case STATIC = 'static';
    case SSR = 'ssr';
    case FLUTTER = 'flutter';
}
