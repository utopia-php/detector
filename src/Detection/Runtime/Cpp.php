<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class CPP extends Runtime
{
    public function getName(): string
    {
        return 'CPP';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['C++'];
    }

    public function getCommands(): string
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
