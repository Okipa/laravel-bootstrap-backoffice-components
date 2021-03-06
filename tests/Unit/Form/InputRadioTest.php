<?php

namespace Okipa\LaravelBootstrapComponents\Tests\Unit\Form;

use Okipa\LaravelBootstrapComponents\Components\ComponentAbstract;
use Okipa\LaravelBootstrapComponents\Facades\InputRadio;
use Okipa\LaravelBootstrapComponents\Tests\Dummy\CustomComponents\CustomInputCheckbox;
use Okipa\LaravelBootstrapComponents\Tests\Unit\Form\Abstracts\InputRadioTestAbstract;

class InputRadioTest extends InputRadioTestAbstract
{
    protected function getComponent(): ComponentAbstract
    {
        return app(config('bootstrap-components.components.radio'))->value('value');
    }

    protected function getHelper(): ComponentAbstract
    {
        return inputRadio();
    }

    protected function getFacade(): ComponentAbstract
    {
        return InputRadio::getFacadeRoot();
    }

    protected function getComponentType(): string
    {
        return 'radio';
    }

    protected function getCustomComponent(): ComponentAbstract
    {
        return (new CustomInputCheckbox());
    }
}
