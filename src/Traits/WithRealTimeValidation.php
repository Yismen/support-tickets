<?php

namespace Dainsys\Support\Traits;

trait WithRealTimeValidation
{
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
