<?php

namespace Utopia\Detector;

abstract class Detector
{
    /**
     * @var array<Detection>
     */
    protected array $options = [];

    /**
     * @param  array<string>  $inputs
     */
    public function __construct(protected array $inputs) {}

    abstract public function detect(): ?Detection;

    public function addOption(Detection $option): self
    {
        $this->options[] = $option;

        return $this;
    }
}
