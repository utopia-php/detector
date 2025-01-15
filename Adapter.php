<?php

namespace Utopia\Detector;

interface Adapter
{
    public function detect(array $inputs): ?Detection;
}
