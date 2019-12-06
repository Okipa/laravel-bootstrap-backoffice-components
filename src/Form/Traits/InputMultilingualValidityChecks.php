<?php

namespace Okipa\LaravelBootstrapComponents\Form\Traits;

use Closure;
use Exception;
use InvalidArgumentException;

trait InputMultilingualValidityChecks
{
    /**
     * Check the component values validity
     *
     * @return void
     * @throws Exception
     */
    protected function checkValuesValidity(): void
    {
        if (! $this->name) {
            throw new Exception(
                get_class($this) . ' : Missing $name property. Please use the name() method to set a name.'
            );
        }
        if ($this->multilingualMode() && $this->value && ! $this->value instanceof Closure) {
            throw new InvalidArgumentException('A multilingual component value has to be set from this
            closure result : « value(function($locale){}) ».');
        }
    }
}
