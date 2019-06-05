<?php

namespace Okipa\LaravelBootstrapComponents\Form;

abstract class Checkable extends Input
{
    /**
     * The input checked status.
     *
     * @property bool $checked
     */
    protected $checked;

    /**
     * Set the input label.
     *
     * @param bool $checked
     *
     * @return \Okipa\LaravelBootstrapComponents\Form\Input
     */
    public function checked(bool $checked = true): Input
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Set the input values.
     *
     * @return array
     * @throws \Exception
     */
    protected function values(): array
    {
        $parentValues = parent::values();
        $oldValue = old($this->name);
        if (isset($oldValue)) {
            $this->checked = $oldValue;
        } elseif ($parentValues['value'] && ! isset($this->checked)) {
            $this->checked = true;
        }

        return array_merge($parentValues, [
            'componentHtmlAttributes' => array_merge(
                $parentValues['componentHtmlAttributes'],
                $this->checked ? ['checked' => 'checked'] : []
            ),
        ]);
    }
}
