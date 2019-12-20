<?php

namespace Okipa\LaravelBootstrapComponents\Tests\Unit\Form;

use Okipa\LaravelBootstrapComponents\ComponentAbstract;
use Okipa\LaravelBootstrapComponents\Facades\InputText;
use Okipa\LaravelBootstrapComponents\Tests\Dummy\CustomComponents\CustomText;
use Okipa\LaravelBootstrapComponents\Tests\Unit\Form\Abstracts\InputMultilingualTestAbstract;

class InputTextTest extends InputMultilingualTestAbstract
{
    protected function getComponent(): ComponentAbstract
    {
        return app(config('bootstrap-components.form.components.text'));
    }

    protected function getHelper(): ComponentAbstract
    {
        return inputText();
    }

    protected function getFacade()
    {
        return InputText::getFacadeRoot();
    }

    protected function getCustomComponent(): ComponentAbstract
    {
        return (new CustomText);
    }

    protected function getComponentType(): string
    {
        return 'text';
    }
}