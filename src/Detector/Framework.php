<?php

namespace Utopia\Detector\Detector;

use Utopia\Detector\Detection\Framework as FrameworkDetection;
use Utopia\Detector\Detector;
use Utopia\Detector\Strategy;

class Framework extends Detector
{
    public function __construct(
        protected array $inputs,
        protected Strategy $strategy,
        protected string $packager = 'npm'
    ) {}

    public function detect(): ?FrameworkDetection
    {
        switch ($this->strategy) {
            case Strategy::FILEMATCH:
                foreach ($this->options as $detector) {
                    $detectorFiles = $detector->getFiles();

                    // Check for exact matches and pattern matches
                    foreach ($this->inputs as $input) {
                        foreach ($detectorFiles as $pattern) {
                            // Check exact match
                            if ($input === $pattern) {
                                return $detector;
                            }

                            // Check if pattern ends with '/' for directory matching
                            if (str_ends_with($pattern, '/') && str_starts_with($input, rtrim($pattern, '/'))) {
                                return $detector;
                            }
                        }
                    }
                }
                break;

            case Strategy::EXTENSION:
                foreach ($this->options as $detector) {
                    foreach ($this->inputs as $file) {
                        if (in_array(pathinfo($file, PATHINFO_EXTENSION), $detector->getFileExtensions())) {
                            return $detector;
                        }
                    }
                }
                break;

            case Strategy::LANGUAGES:
                $matchCounts = [];

                // Count matches for each framework
                foreach ($this->options as $detector) {
                    $matchCount = 0;
                    foreach ($this->inputs as $language) {
                        if (in_array($language, $detector->getLanguages())) {
                            $matchCount++;
                        }
                    }
                    if ($matchCount > 0) {
                        $matchCounts[$detector->getName()] = [
                            'detector' => $detector,
                            'count' => $matchCount,
                        ];
                    }
                }

                // Return framework with most language matches
                if (! empty($matchCounts)) {
                    $maxCount = max(array_column($matchCounts, 'count'));
                    foreach ($matchCounts as $data) {
                        if ($data['count'] === $maxCount) {
                            return $data['detector'];
                        }
                    }
                }
                break;
        }

        return null;
    }
}
