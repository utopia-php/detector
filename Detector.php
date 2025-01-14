<?php

namespace Utopia\Detector;

class Detector
{

    private array $inputs;
    private Adapter $adapter;

    public function _construct(array $inputs, Adapter $adapter)
    {
        $this->inputs = $inputs;
        $this->adapter = $adapter;
    }

    public function detect(): ?Detection
    {
        return $this->adapter->detect($this->inputs);
    }
}
