<?php

namespace Utopia\Detector\Detection\Runtime;

use Utopia\Detector\Detection\Runtime;

class Ruby extends Runtime
{
    public function getName(): string
    {
        return 'ruby';
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return ['Ruby'];
    }

    public function getCommands(): string
    {
        return 'bundle install && bundle exec rake build';
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        return ['rb'];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return ['Gemfile', 'Gemfile.lock', 'Rakefile', 'Guardfile'];
    }

    public function getEntrypoint(): string
    {
        return 'main.rb';
    }
}
