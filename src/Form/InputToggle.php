<?php

namespace Okipa\LaravelBootstrapComponents\Form;

class InputToggle extends Input
{
    /**
     * The component config key.
     *
     * @property string $view
     */
    protected $configKey = 'input_toggle';
    /**
     * The input type.
     *
     * @property string $type
     */
    protected $type = 'toggle';

    /**
     * Set the input values.
     *
     * @return array
     * @throws \Exception
     */
    protected function values(): array
    {
        $parentValues = parent::values();

        return array_merge($parentValues, [
            'type'    => 'toggle',
            'checked' => $parentValues['value'] ? true : false,
        ]);
    }
}