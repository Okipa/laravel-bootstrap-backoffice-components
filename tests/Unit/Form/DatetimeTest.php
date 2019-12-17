<?php

namespace Okipa\LaravelBootstrapComponents\Tests\Unit\Form;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\MessageBag;
use Okipa\LaravelBootstrapComponents\Form\Input;
use Okipa\LaravelBootstrapComponents\Form\Temporal;
use Okipa\LaravelBootstrapComponents\Test\BootstrapComponentsTestCase;
use Okipa\LaravelBootstrapComponents\Test\Fakers\UsersFaker;

class DatetimeTest extends BootstrapComponentsTestCase
{
    use UsersFaker;

    public function testConfigStructure()
    {
        // components.form
        $this->assertTrue(array_key_exists('datetime', config('bootstrap-components.form')));
        // components.form.datetime
        $this->assertTrue(array_key_exists('view', config('bootstrap-components.form.datetime')));
        $this->assertTrue(array_key_exists('prepend', config('bootstrap-components.form.datetime')));
        $this->assertTrue(array_key_exists('append', config('bootstrap-components.form.datetime')));
        $this->assertTrue(array_key_exists('format', config('bootstrap-components.form.datetime')));
        $this->assertTrue(array_key_exists('labelPositionedAbove', config('bootstrap-components.form.datetime')));
        $this->assertTrue(array_key_exists('legend', config('bootstrap-components.form.datetime')));
        $this->assertTrue(array_key_exists('classes', config('bootstrap-components.form.datetime')));
        $this->assertTrue(array_key_exists('htmlAttributes', config('bootstrap-components.form.datetime')));
        // components.form.datetime.classes
        $this->assertTrue(array_key_exists('container', config('bootstrap-components.form.datetime.classes')));
        $this->assertTrue(array_key_exists('component', config('bootstrap-components.form.datetime.classes')));
        // components.form.datetime.htmlAttributes
        $this->assertTrue(array_key_exists('container', config('bootstrap-components.form.datetime.htmlAttributes')));
        $this->assertTrue(array_key_exists('component', config('bootstrap-components.form.datetime.htmlAttributes')));
        // components.form.datetime.formValidation
        $this->assertTrue(array_key_exists(
            'displaySuccess',
            config('bootstrap-components.form.datetime.formValidation')
        ));
        $this->assertTrue(array_key_exists(
            'displayFailure',
            config('bootstrap-components.form.datetime.formValidation')
        ));
    }

    public function testExtendsInput()
    {
        $this->assertEquals(Temporal::class, get_parent_class(bsDatetime()));
    }

    public function testSetName()
    {
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString(' name="name"', $html);
    }

    public function testType()
    {
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString(' type="datetime-local"', $html);
    }

    public function testInputWithoutName()
    {
        $this->expectException(Exception::class);
        bsDatetime()->toHtml();
    }

    public function testWrongModelValue()
    {
        $user = $this->createUniqueUser();
        $user->name = 'test-custom-name';
        $this->expectException(Exception::class);
        bsDatetime()->model($user)->name('name')->toHtml();
    }

    public function testModelDateTimeObjectValue()
    {
        $user = $this->createUniqueUser();
        $user->published_at = $this->faker->dateTime;
        $html = bsDatetime()->model($user)->name('published_at')->toHtml();
        $this->assertStringContainsString(
            ' value="' . $user->published_at->format(config('bootstrap-components.form.datetime.format')) . '"',
            $html
        );
    }

    public function testModelDateTimeStringValue()
    {
        $user = $this->createUniqueUser();
        $user->published_at = $this->faker->dateTime->format('Y-m-d H:i:s');
        $html = bsDatetime()->model($user)->name('published_at')->toHtml();
        $this->assertStringContainsString(
            ' value="' . Carbon::parse($user->published_at)->format(config('bootstrap-components.form.datetime.format'))
            . '"',
            $html
        );
    }

    public function testSetConfigFormat()
    {
        $configFormat = 'Y-m-d H:i:s';
        config()->set('bootstrap-components.form.datetime.format', $configFormat);
        $user = $this->createUniqueUser();
        $user->published_at = $this->faker->dateTime;
        $html = bsDatetime()->model($user)->name('published_at')->toHtml();
        $this->assertStringContainsString($user->published_at->format($configFormat), $html);
    }

    public function testSetFormat()
    {
        $configFormat = 'Y-m-d H:i:s';
        $customFormat = 'Y-m-d H:i';
        config()->set('bootstrap-components.form.datetime.format', $configFormat);
        $user = $this->createUniqueUser();
        $user->published_at = $this->faker->dateTime;
        $html = bsDatetime()->model($user)->name('published_at')->format($customFormat)->toHtml();
        $this->assertStringContainsString($user->published_at->format($customFormat), $html);
    }

    public function testNoFormat()
    {
        config()->set('bootstrap-components.form.datetime.format', null);
        $user = $this->createUniqueUser();
        $user->published_at = $this->faker->dateTime;
        $this->expectException(Exception::class);
        bsDatetime()->model($user)->name('published_at')->toHtml();
    }

    public function testConfigPrepend()
    {
        $configPrepend = 'test-config-prepend';
        config()->set('bootstrap-components.form.datetime.prepend', $configPrepend);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString('input-group-prepend', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $configPrepend . '</span>', $html);
    }

    public function testSetPrepend()
    {
        $configPrepend = 'test-config-prepend';
        $customPrepend = 'test-custom-prepend';
        config()->set('bootstrap-components.form.datetime.prepend', $configPrepend);
        $html = bsDatetime()->name('name')->prepend($customPrepend)->toHtml();
        $this->assertStringContainsString('input-group-prepend', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $customPrepend . '</span>', $html);
        $this->assertStringNotContainsString('<span class="input-group-text">' . $configPrepend . '</span>', $html);
    }

    public function testNoPrepend()
    {
        config()->set('bootstrap-components.form.datetime.prepend', null);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringNotContainsString('input-group-prepend', $html);
    }

    public function testHidePrepend()
    {
        $configPrepend = 'test-config-prepend';
        config()->set('bootstrap-components.form.datetime.prepend', $configPrepend);
        $html = bsDatetime()->name('name')->prepend(false)->toHtml();
        $this->assertStringNotContainsString('input-group-prepend', $html);
    }

    public function testConfigAppend()
    {
        $configAppend = 'test-config-append';
        config()->set('bootstrap-components.form.datetime.append', $configAppend);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString('input-group-append', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $configAppend . '</span>', $html);
    }

    public function testSetAppend()
    {
        $configAppend = 'test-config-append';
        $customAppend = 'test-custom-append';
        config()->set('bootstrap-components.form.datetime.append', $configAppend);
        $html = bsDatetime()->name('name')->append($customAppend)->toHtml();
        $this->assertStringContainsString('input-group-append', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $customAppend . '</span>', $html);
        $this->assertStringNotContainsString('<span class="input-group-text">' . $configAppend . '</span>', $html);
    }

    public function testNoAppend()
    {
        config()->set('bootstrap-components.form.datetime.append', null);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringNotContainsString('input-group-append', $html);
    }

    public function testHideAppend()
    {
        $configAppend = 'test-config-append';
        config()->set('bootstrap-components.form.datetime.append', $configAppend);
        $html = bsDatetime()->name('name')->append(false)->toHtml();
        $this->assertStringNotContainsString('input-group-append', $html);
    }

    public function testNoPrependNoAppend()
    {
        config()->set('bootstrap-components.form.datetime.prepend', null);
        config()->set('bootstrap-components.form.datetime.append', null);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringNotContainsString('<div class="input-group">', $html);
    }

    public function testHidePrependHideAppend()
    {
        $configPrepend = 'test-config-prepend';
        $configAppend = 'test-config-append';
        config()->set('bootstrap-components.form.datetime.prepend', $configPrepend);
        config()->set('bootstrap-components.form.datetime.append', $configAppend);
        $html = bsDatetime()->name('name')->prepend(false)->append(false)->toHtml();
        $this->assertStringNotContainsString('<div class="input-group">', $html);
    }

    public function testConfigLegend()
    {
        $configLegend = 'test-config-legend';
        config()->set('bootstrap-components.form.datetime.legend', $configLegend);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString('datetime-local-name-legend', $html);
        $this->assertStringContainsString('bootstrap-components::' . $configLegend, $html);
    }

    public function testSetLegend()
    {
        $configLegend = 'test-config-legend';
        $customLegend = 'test-custom-legend';
        config()->set('bootstrap-components.form.datetime.legend', $configLegend);
        $html = bsDatetime()->name('name')->legend($customLegend)->toHtml();
        $this->assertStringContainsString('datetime-local-name-legend', $html);
        $this->assertStringContainsString($customLegend, $html);
        $this->assertStringNotContainsString($configLegend, $html);
    }

    public function testSetTranslatedLegend()
    {
        $legend = 'bootstrap-components::bootstrap-components.label.validate';
        $html = bsDatetime()->name('name')->legend($legend)->toHtml();
        $this->assertStringContainsString(__($legend), $html);
    }

    public function testNoLegend()
    {
        config()->set('bootstrap-components.form.datetime.legend', null);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringNotContainsString('datetime-local-name-legend', $html);
    }

    public function testHideLegend()
    {
        $configLegend = 'test-config-legend';
        config()->set('bootstrap-components.form.datetime.legend', $configLegend);
        $html = bsDatetime()->name('name')->legend(false)->toHtml();
        $this->assertStringNotContainsString('datetime-local-name-legend', $html);
    }

    public function testSetWrongValue()
    {
        $customValue = 'test-custom-value';
        $this->expectException(Exception::class);
        bsDatetime()->name('name')->value($customValue)->toHtml();
    }

    public function testSetValue()
    {
        $customValue = $this->faker->dateTime;
        $html = bsDatetime()->name('name')->value($customValue)->toHtml();
        $this->assertStringContainsString(
            ' value="' . $customValue->format(config('bootstrap-components.form.datetime.format')) . '"',
            $html
        );
    }

    public function testOldValue()
    {
        $oldValue = $this->faker->dateTime->format('Y-m-d H:i:s');
        $customValue = $this->faker->dateTime->format('Y-m-d H:i:s');
        $this->app['router']->get('test', [
            'middleware' => 'web', 'uses' => function () use ($oldValue) {
                $request = request()->merge(['name' => $oldValue]);
                $request->flash();
            },
        ]);
        $this->call('GET', 'test');
        $html = bsDatetime()->name('name')->value($customValue)->toHtml();
        $this->assertStringContainsString(' value="' . $oldValue . '"', $html);
        $this->assertStringNotContainsString(' value="' . $customValue . '"', $html);
    }

    public function testSetLabel()
    {
        $label = 'test-custom-label';
        $html = bsDatetime()->name('name')->label($label)->toHtml();
        $this->assertStringContainsString('<label for="datetime-local-name">' . $label . '</label>', $html);
        $this->assertStringContainsString(' placeholder="' . $label . '"', $html);
    }

    public function testSetTranslatedLabel()
    {
        $label = 'bootstrap-components::bootstrap-components.label.validate';
        $html = bsDatetime()->name('name')->label($label)->toHtml();
        $this->assertStringContainsString('<label for="datetime-local-name">' . __($label) . '</label>', $html);
        $this->assertStringContainsString(' placeholder="' . __($label) . '"', $html);
    }

    public function testNoLabel()
    {
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString(
            '<label for="datetime-local-name">validation.attributes.name</label>',
            $html
        );
    }

    public function testHideLabel()
    {
        $html = bsDatetime()->name('name')->label(false)->toHtml();
        $this->assertStringNotContainsString(
            '<label for="date-name">validation.attributes.name</label>',
            $html
        );
    }

    public function testConfigLabelPositionedAbove()
    {
        config()->set('bootstrap-components.form.datetime.labelPositionedAbove', true);
        $html = bsDatetime()->name('name')->toHtml();
        $labelPosition = strrpos($html, '<label for="');
        $inputPosition = strrpos($html, '<input');
        $this->assertLessThan($inputPosition, $labelPosition);
    }

    public function testConfigLabelPositionedUnder()
    {
        config()->set('bootstrap-components.form.datetime.labelPositionedAbove', false);
        $html = bsDatetime()->name('name')->toHtml();
        $labelPosition = strrpos($html, '<label for="');
        $inputPosition = strrpos($html, '<input');
        $this->assertLessThan($labelPosition, $inputPosition);
    }

    public function testLabelPositionedAbove()
    {
        config()->set('bootstrap-components.form.datetime.labelPositionedAbove', false);
        $html = bsDatetime()->name('name')->labelPositionedAbove()->toHtml();
        $labelPosition = strrpos($html, '<label for="');
        $inputPosition = strrpos($html, '<input');
        $this->assertLessThan($inputPosition, $labelPosition);
    }

    public function testLabelPositionedUnder()
    {
        config()->set('bootstrap-components.form.datetime.labelPositionedAbove', true);
        $html = bsDatetime()->name('name')->labelPositionedAbove(false)->toHtml();
        $labelPosition = strrpos($html, '<label for="');
        $inputPosition = strrpos($html, '<input');
        $this->assertLessThan($labelPosition, $inputPosition);
    }

    public function testSetPlaceholder()
    {
        $placeholder = 'test-custom-placeholder';
        $html = bsDatetime()->name('name')->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString(' placeholder="' . $placeholder . '"', $html);
    }

    public function testSetTranslatedPlaceholder()
    {
        $placeholder = 'bootstrap-components::bootstrap-components.label.validate';
        $html = bsDatetime()->name('name')->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString(' placeholder="' . __($placeholder) . '"', $html);
    }

    public function testSetPlaceholderWithLabel()
    {
        $label = 'test-custom-label';
        $placeholder = 'test-custom-placeholder';
        $html = bsDatetime()->name('name')->label($label)->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString(' placeholder="' . $placeholder . '"', $html);
    }

    public function testNoPlaceholder()
    {
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString(' placeholder="validation.attributes.name"', $html);
    }

    public function testNoPlaceholderWithNoLabel()
    {
        $html = bsDatetime()->name('name')->label(false)->toHtml();
        $this->assertStringContainsString(' placeholder="validation.attributes.name"', $html);
    }

    public function testHidePlaceholder()
    {
        $html = bsDatetime()->name('name')->placeholder(false)->toHtml();
        $this->assertStringNotContainsString(' placeholder="', $html);
    }

    public function testConfigDisplaySuccess()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displaySuccess', true);
        $errors = app(MessageBag::class)->add('other_name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->render(compact('errors'));
        $this->assertStringContainsString('is-valid', $html);
        $this->assertStringContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testConfigDoNotDisplaySuccess()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displaySuccess', false);
        $errors = app(MessageBag::class)->add('other_name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->render(compact('errors'));
        $this->assertStringNotContainsString('is-valid', $html);
        $this->assertStringNotContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringNotContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testDisplaySuccess()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displaySuccess', false);
        $errors = app(MessageBag::class)->add('other_name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->displaySuccess()->render(compact('errors'));
        $this->assertStringContainsString('is-valid', $html);
        $this->assertStringContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testDoNotDisplaySuccess()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displaySuccess', true);
        $errors = app(MessageBag::class)->add('other_name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->displaySuccess(false)->render(compact('errors'));
        $this->assertStringNotContainsString('is-valid', $html);
        $this->assertStringNotContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringNotContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testConfigDisplayFailure()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displayFailure', true);
        $errors = app(MessageBag::class)->add('name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->render(compact('errors'));
        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringContainsString($errors->first('name'), $html);
    }

    public function testConfigDoNotDisplayFailure()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displayFailure', false);
        $errors = app(MessageBag::class)->add('name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->render(compact('errors'));
        $this->assertStringNotContainsString('is-invalid', $html);
        $this->assertStringNotContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringNotContainsString($errors->first('name'), $html);
    }

    public function testDisplayFailure()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displayFailure', false);
        $errors = app(MessageBag::class)->add('name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->displayFailure()->render(compact('errors'));
        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringContainsString($errors->first('name'), $html);
    }

    public function testDoNotDisplayFailure()
    {
        config()->set('bootstrap-components.form.datetime.formValidation.displayFailure', true);
        $errors = app(MessageBag::class)->add('name', 'Dummy error message.');
        session()->put('errors', $errors);
        $html = bsDatetime()->name('name')->displayFailure(false)->render(compact('errors'));
        $this->assertStringNotContainsString('is-invalid', $html);
        $this->assertStringNotContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringNotContainsString($errors->first('name'), $html);
    }

    public function testSetNoContainerId()
    {
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringNotContainsString('<div id="', $html);
    }

    public function testSetContainerId()
    {
        $customContainerId = 'test-custom-container-id';
        $html = bsDatetime()->name('name')->containerId($customContainerId)->toHtml();
        $this->assertStringContainsString('<div id="' . $customContainerId . '"', $html);
    }

    public function testSetNoComponentId()
    {
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString(' for="datetime-local-name"', $html);
        $this->assertStringContainsString('<input id="datetime-local-name"', $html);
    }

    public function testSetComponentId()
    {
        $customComponentId = 'test-custom-component-id';
        $html = bsDatetime()->name('name')->componentId($customComponentId)->toHtml();
        $this->assertStringContainsString(' for="' . $customComponentId . '"', $html);
        $this->assertStringContainsString('<input id="' . $customComponentId . '"', $html);
    }

    public function testConfigContainerClasses()
    {
        $configContainerClasses = 'test-config-class-container';
        config()->set('bootstrap-components.form.datetime.classes.container', [$configContainerClasses]);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString(
            'class="component-container ' . $configContainerClasses . '"',
            $html
        );
    }

    public function testSetContainerClasses()
    {
        $configContainerClasses = 'test-config-class-container';
        $customContainerClasses = 'test-custom-class-container';
        config()->set('bootstrap-components.form.datetime.classes.container', [$configContainerClasses]);
        $html = bsDatetime()->name('name')->containerClasses([$customContainerClasses])->toHtml();
        $this->assertStringContainsString(
            'class="component-container ' . $customContainerClasses . '"',
            $html
        );
        $this->assertStringNotContainsString(
            'class="component-container ' . $configContainerClasses . '"',
            $html
        );
    }

    public function testConfigComponentClass()
    {
        $configComponentClasses = 'test-config-class-component';
        config()->set('bootstrap-components.form.datetime.classes.component', [$configComponentClasses]);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString(
            'class="component form-control ' . $configComponentClasses . '"',
            $html
        );
    }

    public function testSetComponentClass()
    {
        $configComponentClasses = 'test-config-class-component';
        $customComponentClasses = 'test-custom-class-component';
        config()->set('bootstrap-components.form.datetime.classes.component', [$customComponentClasses]);
        $html = bsDatetime()->name('name')->componentClasses([$customComponentClasses])->toHtml();
        $this->assertStringContainsString(
            'class="component form-control ' . $customComponentClasses . '"',
            $html
        );
        $this->assertStringNotContainsString(
            'class="component form-control ' . $configComponentClasses . '"',
            $html
        );
    }

    public function testConfigContainerHtmlAttributes()
    {
        $configContainerAttributes = 'test-config-attributes-container';
        config()->set('bootstrap-components.form.datetime.htmlAttributes.container', [$configContainerAttributes]);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString($configContainerAttributes, $html);
    }

    public function testSetContainerHtmlAttributes()
    {
        $configContainerAttributes = 'test-config-attributes-container';
        $customContainerAttributes = 'test-custom-attributes-container';
        config()->set('bootstrap-components.form.datetime.htmlAttributes.container', [$configContainerAttributes]);
        $html = bsDatetime()->name('name')->containerHtmlAttributes([$customContainerAttributes])->toHtml();
        $this->assertStringContainsString($customContainerAttributes, $html);
        $this->assertStringNotContainsString($configContainerAttributes, $html);
    }

    public function testConfigComponentHtmlAttributes()
    {
        $configComponentAttributes = 'test-config-attributes-component';
        config()->set('bootstrap-components.form.datetime.htmlAttributes.component', [$configComponentAttributes]);
        $html = bsDatetime()->name('name')->toHtml();
        $this->assertStringContainsString($configComponentAttributes, $html);
    }

    public function testSetComponentHtmlAttributes()
    {
        $configComponentAttributes = 'test-config-attributes-component';
        $customComponentAttributes = 'test-custom-attributes-component';
        config()->set('bootstrap-components.form.datetime.htmlAttributes.component', [$configComponentAttributes]);
        $html = bsDatetime()->name('name')->componentHtmlAttributes([$customComponentAttributes])->toHtml();
        $this->assertStringContainsString($customComponentAttributes, $html);
        $this->assertStringNotContainsString($configComponentAttributes, $html);
    }
}
