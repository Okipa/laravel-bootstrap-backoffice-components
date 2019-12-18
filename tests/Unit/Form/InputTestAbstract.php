<?php

namespace Okipa\LaravelBootstrapComponents\Tests\Unit\Form;

use Exception;
use Illuminate\Support\MessageBag;
use Okipa\LaravelBootstrapComponents\Component;
use Okipa\LaravelBootstrapComponents\Facades\Input;
use Okipa\LaravelBootstrapComponents\Form\Abstracts\Form;
use Okipa\LaravelBootstrapComponents\Tests\BootstrapComponentsTestCase;
use Okipa\LaravelBootstrapComponents\Tests\Fakers\UsersFaker;

abstract class InputTestAbstract extends BootstrapComponentsTestCase
{
    use UsersFaker;

    abstract protected function getCustomComponent(): Component;

    abstract protected function getComponent(): Component;

    abstract protected function getComponentType(): string;

    public function testHelper()
    {
        $this->assertInstanceOf(get_class($this->getComponent()), input()->{$this->getComponentKey()}());
    }

    protected function getComponentKey(): string
    {
        return $this->getComponentType();
    }

    public function testFacade()
    {
        $this->assertInstanceOf(get_class($this->getComponent()), Input::{$this->getComponentKey()}());
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Form::class, $this->getComponent());
    }

    public function testSetName()
    {
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString(' name="name"', $html);
    }

    public function testType()
    {
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString(' type="' . $this->getComponentType() . '"', $html);
    }

    public function testInputWithoutName()
    {
        $this->expectException(Exception::class);
        $this->getComponent()->toHtml();
    }

    public function testModelValue()
    {
        $user = $this->createUniqueUser();
        $html = $this->getComponent()->model($user)->name('name')->toHtml();
        $this->assertStringContainsString(' value="' . $user->name . '"', $html);
    }

    public function testSetCustomPrepend()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('<span class="input-group-text">default-prepend</span>', $html);
    }

    public function testSetPrependOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->prepend('custom-prepend')->toHtml();
        $this->assertStringContainsString('<span class="input-group-text">custom-prepend</span>', $html);
        $this->assertStringNotContainsString('<span class="input-group-text">default-prepend</span>', $html);
    }

    public function testHidePrepend()
    {
        $html = $this->getComponent()->name('name')->prepend(null)->toHtml();
        $this->assertStringNotContainsString('<div class="input-group-prepend">', $html);
    }

    public function testSetCustomAppend()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('<span class="input-group-text">default-append</span>', $html);
    }

    public function testSetAppendOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->append('custom-append')->toHtml();
        $this->assertStringContainsString('<span class="input-group-text">custom-append</span>', $html);
        $this->assertStringNotContainsString('<span class="input-group-text">default-append</span>', $html);
    }

    public function testHideAppend()
    {
        $html = $this->getComponent()->name('name')->append(null)->toHtml();
        $this->assertStringNotContainsString('<div class="input-group-append">', $html);
    }

    public function testHidePrependHideAppend()
    {
        $html = $this->getComponent()->name('name')->prepend(null)->append(null)->toHtml();
        $this->assertStringNotContainsString('<div class="input-group">', $html);
    }

    public function testSetCustomLegend()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('class="legend form-text text-muted">default-legend', $html);
    }

    public function testSetLegendOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->legend('custom-legend')->toHtml();
        $this->assertStringContainsString('class="legend form-text text-muted">custom-legend', $html);
        $this->assertStringNotContainsString('class="legend form-text text-muted">default-legend', $html);
    }

    public function testSetTranslatedLegend()
    {
        $legend = 'bootstrap-components::bootstrap-components.label.validate';
        $html = $this->getComponent()->name('name')->legend($legend)->toHtml();
        $this->assertStringContainsString(__($legend), $html);
    }

    public function testHideLegend()
    {
        $html = $this->getComponent()->name('name')->legend(null)->toHtml();
        $this->assertStringNotContainsString('class="legend form-text text-muted"', $html);
    }

    public function testSetValue()
    {
        $customValue = 'test-custom-value';
        $html = $this->getComponent()->name('name')->value($customValue)->toHtml();
        $this->assertStringContainsString(' value="' . $customValue . '"', $html);
    }

    public function testOldValue()
    {
        $oldValue = 'test-old-value';
        $customValue = 'test-custom-value';
        $this->app['router']->get('test', [
            'middleware' => 'web', 'uses' => function() use ($oldValue) {
                $request = request()->merge(['name' => $oldValue]);
                $request->flash();
            },
        ]);
        $this->call('GET', 'test');
        $html = $this->getComponent()->name('name')->value($customValue)->toHtml();
        $this->assertStringContainsString(' value="' . $oldValue . '"', $html);
        $this->assertStringNotContainsString(' value="' . $customValue . '"', $html);
    }

    public function testSetLabel()
    {
        $label = 'test-custom-label';
        $html = $this->getComponent()->name('name')->label($label)->toHtml();
        $this->assertStringContainsString(
            '<label for="' . $this->getComponentType() . '-name">' . $label . '</label>',
            $html
        );
    }

    public function testSetTranslatedLabel()
    {
        $label = 'bootstrap-components::bootstrap-components.label.validate';
        $html = $this->getComponent()->name('name')->label($label)->toHtml();
        $this->assertStringContainsString(
            '<label for="' . $this->getComponentType() . '-name">' . __($label) . '</label>',
            $html
        );
    }

    public function testNoLabel()
    {
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString(
            '<label for="' . $this->getComponentType() . '-name">validation.attributes.name</label>',
            $html
        );
    }

    public function testHideLabel()
    {
        $html = $this->getComponent()->name('name')->label(false)->toHtml();
        $this->assertStringNotContainsString(
            '<label for="' . $this->getComponentType() . '-name">validation.attributes.name</label>',
            $html
        );
    }

    public function testSetCustomLabelPositionedAbove()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $labelPosition = strrpos($html, '<label for="');
        $inputPosition = strrpos($html, '<input');
        $this->assertLessThan($labelPosition, $inputPosition);
    }

    public function testSetLabelPositionedAboveOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->labelPositionedAbove()->toHtml();
        $labelPosition = strrpos($html, '<label for="');
        $inputPosition = strrpos($html, '<input');
        $this->assertLessThan($inputPosition, $labelPosition);
    }

    public function testSetPlaceholder()
    {
        $placeholder = 'test-custom-placeholder';
        $html = $this->getComponent()->name('name')->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString(' placeholder="' . $placeholder . '"', $html);
    }

    public function testSetTranslatedPlaceholder()
    {
        $placeholder = 'bootstrap-components::bootstrap-components.label.validate';
        $html = $this->getComponent()->name('name')->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString(' placeholder="' . __($placeholder) . '"', $html);
    }

    public function testSetPlaceholderWithLabel()
    {
        $label = 'test-custom-label';
        $placeholder = 'test-custom-placeholder';
        $html = $this->getComponent()->name('name')->label($label)->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString(' placeholder="' . $placeholder . '"', $html);
        $this->assertStringNotContainsString(' placeholder="' . $label . '"', $html);
    }

    public function testNoPlaceholder()
    {
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString(' placeholder="validation.attributes.name"', $html);
    }

    public function testNoPlaceholderWithNoLabel()
    {
        $html = $this->getComponent()->name('name')->label(false)->toHtml();
        $this->assertStringContainsString(' placeholder="validation.attributes.name"', $html);
    }

    public function testHidePlaceholder()
    {
        $html = $this->getComponent()->name('name')->placeholder(false)->toHtml();
        $this->assertStringNotContainsString(' placeholder="', $html);
    }

    public function testSetCustomDisplaySuccess()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $errors = app(MessageBag::class)->add('other_name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = $this->getComponent()->name('name')->render(compact('errors'));
        $this->assertStringContainsString('is-valid', $html);
        $this->assertStringContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testSetDisplaySuccessOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $errors = app(MessageBag::class)->add('other_name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = $this->getComponent()->name('name')->displaySuccess(false)->render(compact('errors'));
        $this->assertStringNotContainsString('is-valid', $html);
        $this->assertStringNotContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringNotContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testSetCustomDisplayFailure()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $errors = app(MessageBag::class)->add('name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = $this->getComponent()->name('name')->render(compact('errors'));
        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringContainsString($errors->first('name'), $html);
    }

    public function testSetDisplayFailureOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $errors = app(MessageBag::class)->add('name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = $this->getComponent()->name('name')->displayFailure(false)->render(compact('errors'));
        $this->assertStringNotContainsString('is-invalid', $html);
        $this->assertStringNotContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringNotContainsString($errors->first('name'), $html);
    }

    public function testSetNoContainerId()
    {
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringNotContainsString('<div id="', $html);
    }

    public function testSetContainerId()
    {
        $customContainerId = 'test-custom-container-id';
        $html = $this->getComponent()->name('name')->containerId($customContainerId)->toHtml();
        $this->assertStringContainsString('<div id="' . $customContainerId . '"', $html);
    }

    public function testSetNoComponentId()
    {
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString(' for="' . $this->getComponentType() . '-name"', $html);
        $this->assertStringContainsString('<input id="' . $this->getComponentType() . '-name"', $html);
    }

    public function testSetComponentId()
    {
        $customComponentId = 'test-custom-component-id';
        $html = $this->getComponent()->name('name')->componentId($customComponentId)->toHtml();
        $this->assertStringContainsString(' for="' . $customComponentId . '"', $html);
        $this->assertStringContainsString('<input id="' . $customComponentId . '"', $html);
    }

    public function testSetCustomContainerClasses()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('class="component-container form-group default container classes"', $html);
    }

    public function testSetContainerClassesOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->containerClasses(['custom', 'container', 'classes'])->toHtml();
        $this->assertStringContainsString('class="component-container form-group custom container classes"', $html);
        $this->assertStringNotContainsString('class="component-container form-group default container classes"', $html);
    }

    public function testSetCustomComponentClasses()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('class="component form-control default component classes"', $html);
    }

    public function testSetComponentClassesOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->componentClasses(['custom', 'component', 'classes'])->toHtml();
        $this->assertStringContainsString('class="component form-control custom component classes"', $html);
        $this->assertStringNotContainsString('class="component form-control default component classes"', $html);
    }

    public function testSetCustomContainerHtmlAttributes()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString(
            'default="container" html="attributes">',
            $html
        );
    }

    public function testSetContainerHtmlAttributesOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()
            ->name('name')
            ->containerHtmlAttributes(['custom' => 'container', 'html' => 'attributes'])
            ->toHtml();
        $this->assertStringContainsString('custom="container" html="attributes">', $html);
        $this->assertStringNotContainsString('default="container" html="attributes">', $html);
    }

    public function testSetCustomComponentHtmlAttributes()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('default="component" html="attributes">', $html);
    }

    public function testSetComponentHtmlAttributesOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')
            ->componentHtmlAttributes(['custom' => 'component', 'html' => 'attributes'])
            ->toHtml();
        $this->assertStringContainsString('custom="component" html="attributes">', $html);
        $this->assertStringNotContainsString('default="component" html="attributes">', $html);
    }
}
