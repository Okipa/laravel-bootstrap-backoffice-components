<?php

namespace Okipa\LaravelBootstrapComponents\Components\Form;

use Okipa\LaravelBootstrapComponents\Components\Form\Abstracts\CheckableAbstract;

class InputCheckbox extends CheckableAbstract
{
    protected function setType(): string
    {
        return 'checkbox';
    }

    protected function setView(): string
    {
        return 'bootstrap-components.form.checkbox';
    }

    protected function setPrepend(): ?string
    {
        return null;
    }

    protected function setAppend(): ?string
    {
        return null;
    }

    protected function setCaption(): ?string
    {
        return null;
    }

    protected function setComponentClasses(): array
    {
        return [];
    }

    protected function setContainerClasses(): array
    {
        return ['form-group'];
    }

    protected function setComponentHtmlAttributes(): array
    {
        return [];
    }

    protected function setContainerHtmlAttributes(): array
    {
        return [];
    }

    protected function setDisplaySuccess(): bool
    {
        return config('bootstrap-components.form.formValidation.displaySuccess');
    }

    protected function setDisplayFailure(): bool
    {
        return config('bootstrap-components.form.formValidation.displayFailure');
    }
}
