<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class Cpp extends Runtime
{
    public function getName(): string
    {
        return 'cpp';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['C++'];
    }

    public function getCommand(): string
    {
        return 'g++ -o main.cpp && ./output';
    }
    // TODO: Verify commands for all runtimes

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['cpp', 'h', 'hpp', 'cxx', 'cc'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['main.cpp', 'Solution', 'CMakeLists.txt', '.clang-format'];
    }

    public function getEntrypoint(): string
    {
        return '';
    }
}
