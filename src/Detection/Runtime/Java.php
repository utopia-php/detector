<?php

namespace Utopia\Detector\Detection;

class Java extends Runtime
{
    public function getName(): string
    {
        return 'java';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['Java'];
    }

    public function getCommand(): string
    {
        return 'mvn install && mvn package';
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['java', 'class', 'jar'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['pom.xml', 'pmd.xml', 'build.gradle', 'build.gradle.kts'];
    }

    public function getEntrypoint(): string
    {
        return 'Main.java';
    }
}
