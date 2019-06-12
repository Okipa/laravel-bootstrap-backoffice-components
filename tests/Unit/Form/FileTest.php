<?php

namespace Okipa\LaravelBootstrapComponents\Tests\Unit\Form;

use Exception;
use Illuminate\Support\MessageBag;
use Okipa\LaravelBootstrapComponents\Form\Input;
use Okipa\LaravelBootstrapComponents\Test\BootstrapComponentsTestCase;
use Okipa\LaravelBootstrapComponents\Test\Fakers\UsersFaker;

class FileTest extends BootstrapComponentsTestCase
{
    use UsersFaker;

    public function testConfigStructure()
    {
        // components.form
        $this->assertTrue(array_key_exists('file', config('bootstrap-components.form')));
        // components.form.input
        $this->assertTrue(array_key_exists('view', config('bootstrap-components.form.file')));
        $this->assertTrue(array_key_exists('prepend', config('bootstrap-components.form.file')));
        $this->assertTrue(array_key_exists('append', config('bootstrap-components.form.file')));
        $this->assertTrue(array_key_exists('show_remove_checkbox', config('bootstrap-components.form.file')));
        $this->assertTrue(array_key_exists('legend', config('bootstrap-components.form.file')));
        $this->assertTrue(array_key_exists('class', config('bootstrap-components.form.file')));
        $this->assertTrue(array_key_exists('htmlAttributes', config('bootstrap-components.form.file')));
        // components.form.file.class
        $this->assertTrue(array_key_exists('container', config('bootstrap-components.form.file.class')));
        $this->assertTrue(array_key_exists('component', config('bootstrap-components.form.file.class')));
        // components.form.file.htmlAttributes
        $this->assertTrue(array_key_exists('container', config('bootstrap-components.form.file.htmlAttributes')));
        $this->assertTrue(array_key_exists('component', config('bootstrap-components.form.file.htmlAttributes')));
    }

    public function testExtendsInput()
    {
        $this->assertEquals(Input::class, get_parent_class(bsFile()));
    }

    public function testSetName()
    {
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString('name="name"', $html);
    }

    public function testInputWithoutName()
    {
        $this->expectException(Exception::class);
        bsFile()->toHtml();
    }

    public function testModelValue()
    {
        $user = $this->createUniqueUser();
        $html = bsFile()->model($user)->name('name')->toHtml();
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="file-name">' . $user->name . '</label>',
            $html
        );
    }

    public function testConfigPrepend()
    {
        $configPrepend = 'test-config-prepend';
        config()->set('bootstrap-components.form.file.prepend', $configPrepend);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString('input-group-prepend', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $configPrepend . '</span>', $html);
    }

    public function testSetPrepend()
    {
        $configPrepend = 'test-config-prepend';
        $customPrepend = 'test-custom-prepend';
        config()->set('bootstrap-components.form.file.prepend', $configPrepend);
        $html = bsFile()->name('name')->prepend($customPrepend)->toHtml();
        $this->assertStringContainsString('input-group-prepend', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $customPrepend . '</span>', $html);
        $this->assertStringNotContainsString('<span class="input-group-text">' . $configPrepend . '</span>', $html);
    }

    public function testNoPrepend()
    {
        config()->set('bootstrap-components.form.file.prepend', null);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringNotContainsString('input-group-prepend', $html);
    }

    public function testHidePrepend()
    {
        $configPrepend = 'test-config-prepend';
        config()->set('bootstrap-components.form.file.prepend', $configPrepend);
        $html = bsFile()->name('name')->prepend(false)->toHtml();
        $this->assertStringNotContainsString('input-group-prepend', $html);
    }

    public function testConfigAppend()
    {
        $configAppend = 'test-config-append';
        config()->set('bootstrap-components.form.file.append', $configAppend);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString('input-group-append', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $configAppend . '</span>', $html);
    }

    public function testSetAppend()
    {
        $configAppend = 'test-config-append';
        $customAppend = 'test-custom-append';
        config()->set('bootstrap-components.form.file.append', $configAppend);
        $html = bsFile()->name('name')->append($customAppend)->toHtml();
        $this->assertStringContainsString('input-group-append', $html);
        $this->assertStringContainsString('<span class="input-group-text">' . $customAppend . '</span>', $html);
        $this->assertStringNotContainsString('<span class="input-group-text">' . $configAppend . '</span>', $html);
    }

    public function testNoAppend()
    {
        config()->set('bootstrap-components.form.file.append', null);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringNotContainsString('input-group-append', $html);
    }

    public function testHideAppend()
    {
        $configAppend = 'test-config-append';
        config()->set('bootstrap-components.form.file.append', $configAppend);
        $html = bsFile()->name('name')->append(false)->toHtml();
        $this->assertStringNotContainsString('input-group-append', $html);
    }

    public function testNoPrependNoAppend()
    {
        config()->set('bootstrap-components.form.file.prepend', null);
        config()->set('bootstrap-components.form.file.append', null);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringNotContainsString('<div class="input-group">', $html);
    }

    public function testHidePrependHideAppend()
    {
        $configPrepend = 'test-config-prepend';
        $configAppend = 'test-config-append';
        config()->set('bootstrap-components.form.file.prepend', $configPrepend);
        config()->set('bootstrap-components.form.file.append', $configAppend);
        $html = bsFile()->name('name')->prepend(false)->append(false)->toHtml();
        $this->assertStringNotContainsString('<div class="input-group">', $html);
    }

    public function testConfigLegend()
    {
        $configLegend = 'test-config-legend';
        config()->set('bootstrap-components.form.file.legend', $configLegend);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString('file-name-legend', $html);
        $this->assertStringContainsString($configLegend, $html);
    }

    public function testSetLegend()
    {
        $configLegend = 'test-config-legend';
        $customLegend = 'test-custom-legend';
        config()->set('bootstrap-components.form.file.legend', $configLegend);
        $html = bsFile()->name('name')->legend($customLegend)->toHtml();
        $this->assertStringContainsString('file-name-legend', $html);
        $this->assertStringContainsString($customLegend, $html);
        $this->assertStringNotContainsString($configLegend, $html);
    }

    public function testNoLegend()
    {
        config()->set('bootstrap-components.form.file.legend', null);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringNotContainsString('file-name-legend', $html);
    }

    public function testHideLegend()
    {
        $configLegend = 'test-config-legend';
        config()->set('bootstrap-components.form.file.legend', $configLegend);
        $html = bsFile()->name('name')->legend(false)->toHtml();
        $this->assertStringNotContainsString('file-name-legend', $html);
    }

    public function testSetValue()
    {
        $customValue = 'test-custom-value';
        $html = bsFile()->name('name')->value($customValue)->toHtml();
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="file-name">' . $customValue . '</label>',
            $html
        );
    }

    public function testOldValue()
    {
        $oldValue = 'test-old-value';
        $customValue = 'test-custom-value';
        $this->app['router']->get('test', [
            'middleware' => 'web', 'uses' => function () use ($oldValue) {
                $request = request()->merge(['name' => $oldValue]);
                $request->flash();
            },
        ]);
        $this->call('GET', 'test');
        $html = bsFile()->name('name')->value($customValue)->toHtml();
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="file-name">' . $oldValue . '</label>',
            $html
        );
        $this->assertStringNotContainsString(
            '<label class="custom-file-label" for="file-name">' . $customValue . '</label>',
            $html
        );
    }

    public function testSetLabel()
    {
        $label = 'test-custom-label';
        $html = bsFile()->name('name')->label($label)->toHtml();
        $this->assertStringContainsString('<label for="file-name">' . $label . '</label>', $html);
        $this->assertStringContainsString('aria-label="' . $label . '"', $html);
    }

    public function testNoLabel()
    {
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString(
            '<label for="file-name">validation.attributes.name</label>',
            $html
        );
        $this->assertStringContainsString(
            'aria-label="validation.attributes.name"',
            $html
        );
    }

    public function testHideLabel()
    {
        $html = bsFile()->name('name')->label(false)->toHtml();
        $this->assertStringNotContainsString(
            '<label for="file-name">validation.attributes.name</label>',
            $html
        );
        $this->assertStringNotContainsString(
            'aria-label="validation.attributes.name"',
            $html
        );
    }

    public function testLabelNotAfter()
    {
        $html = bsFile()->name('name')->labelAfter(false)->toHtml();
        $this->assertStringContainsString(
            '<label for="file-name">validation.attributes.name</label>',
            $html
        );
        $this->assertStringContainsString(
            'aria-label="validation.attributes.name"',
            $html
        );
        $this->assertLessThan(
            strrpos($html, '<input id="file-name"'),
            strrpos($html, '<label for="file-name">validation.attributes.name</label>')
        );
    }

    public function testLabelAfter()
    {
        $html = bsFile()->name('name')->labelAfter(true)->toHtml();
        $this->assertStringContainsString(
            '<label for="file-name">validation.attributes.name</label>',
            $html
        );
        $this->assertStringContainsString(
            'aria-label="validation.attributes.name"',
            $html
        );
        $this->assertGreaterThan(
            strrpos($html, '<input id="file-name"'),
            strrpos($html, '<label for="file-name">validation.attributes.name</label>')
        );
    }

    public function testSetPlaceholder()
    {
        $placeholder = 'test-custom-placeholder';
        $html = bsFile()->name('name')->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString('custom-file-label', $html);
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="file-name">' . $placeholder . '</label>',
            $html
        );
    }

    public function testNoPlaceholder()
    {
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString('custom-file-label', $html);
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="file-name">'
            . __('bootstrap-components::bootstrap-components.label.file') . '</label>',
            $html
        );
    }

    public function testNoPlaceholderWithNoLabel()
    {
        $html = bsFile()->name('name')->label(false)->toHtml();
        $this->assertStringContainsString('custom-file-label', $html);
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="file-name">'
            . __('bootstrap-components::bootstrap-components.label.file') . '</label>',
            $html
        );
    }

    public function testHidePlaceholder()
    {
        $html = bsFile()->name('name')->placeholder(false)->toHtml();
        $this->assertStringNotContainsString('ustom-file-label', $html);
        $this->assertStringNotContainsString(
            '<label class="custom-file-label" for="file-name">'
            . __('bootstrap-components::bootstrap-components.label.file') . '</label>',
            $html
        );
    }

    public function testConfigDisplaySuccess()
    {
        config()->set('bootstrap-components.form.file.formValidation.displaySuccess', true);
        $messageBag = app(MessageBag::class)->add('other_name', null);
        $html = bsFile()->name('name')->render(['errors' => $messageBag]);
        $this->assertStringContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testConfigDoNotDisplaySuccess()
    {
        config()->set('bootstrap-components.form.file.formValidation.displaySuccess', false);
        $messageBag = app(MessageBag::class)->add('other_name', null);
        $html = bsFile()->name('name')->render(['errors' => $messageBag]);
        $this->assertStringNotContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringNotContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testDisplaySuccess()
    {
        config()->set('bootstrap-components.form.file.formValidation.displaySuccess', false);
        $messageBag = app(MessageBag::class)->add('other_name', null);
        $html = bsFile()->name('name')->displaySuccess(true)->render(['errors' => $messageBag]);
        $this->assertStringContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testDoNotDisplaySuccess()
    {
        config()->set('bootstrap-components.form.file.formValidation.displaySuccess', true);
        $messageBag = app(MessageBag::class)->add('other_name', null);
        $html = bsFile()->name('name')->displaySuccess(false)->render(['errors' => $messageBag]);
        $this->assertStringNotContainsString('<div class="valid-feedback d-block">', $html);
        $this->assertStringNotContainsString(
            __('bootstrap-components::bootstrap-components.notification.validation.success'),
            $html
        );
    }

    public function testConfigDisplayFailure()
    {
        config()->set('bootstrap-components.form.file.formValidation.displayFailure', true);
        $errorMessage = 'This a test error message';
        $messageBag = app(MessageBag::class)->add('name', $errorMessage);
        $html = bsFile()->name('name')->render(['errors' => $messageBag]);
        $this->assertStringContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringContainsString($errorMessage, $html);
    }

    public function testConfigDoNotDisplayFailure()
    {
        config()->set('bootstrap-components.form.file.formValidation.displayFailure', false);
        $errorMessage = 'This a test error message';
        $messageBag = app(MessageBag::class)->add('name', $errorMessage);
        $html = bsFile()->name('name')->render(['errors' => $messageBag]);
        $this->assertStringNotContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringNotContainsString($errorMessage, $html);
    }

    public function testDisplayFailure()
    {
        config()->set('bootstrap-components.form.file.formValidation.displayFailure', false);
        $errorMessage = 'This a test error message';
        $messageBag = app(MessageBag::class)->add('name', $errorMessage);
        $html = bsFile()->name('name')->displayFailure(true)->render(['errors' => $messageBag]);
        $this->assertStringContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringContainsString($errorMessage, $html);
    }

    public function testDoNotDisplayFailure()
    {
        config()->set('bootstrap-components.form.file.formValidation.displayFailure', true);
        $errorMessage = 'This a test error message';
        $messageBag = app(MessageBag::class)->add('name', $errorMessage);
        $html = bsFile()->name('name')->displayFailure(false)->render(['errors' => $messageBag]);
        $this->assertStringNotContainsString('<div class="invalid-feedback d-block">', $html);
        $this->assertStringNotContainsString($errorMessage, $html);
    }

    public function testConfigContainerClasses()
    {
        $configContainerClasses = 'test-config-class-container';
        config()->set('bootstrap-components.form.file.class.container', [$configContainerClasses]);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString('class="file-name-container ' . $configContainerClasses . '"', $html);
    }

    public function testSetContainerClasses()
    {
        $configContainerClasses = 'test-config-class-container';
        $customContainerClasses = 'test-custom-class-container';
        config()->set('bootstrap-components.form.file.class.container', [$configContainerClasses]);
        $html = bsFile()->name('name')->containerClasses([$customContainerClasses])->toHtml();
        $this->assertStringContainsString(
            'class="file-name-container ' . $customContainerClasses . '"',
            $html
        );
        $this->assertStringNotContainsString(
            'class="file-name-container ' . $configContainerClasses . '"',
            $html
        );
    }

    public function testSetNoContainerId()
    {
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringNotContainsString('<div id="', $html);
    }

    public function testSetContainerId()
    {
        $customContainerId = 'test-custom-container-id';
        $html = bsFile()->name('name')->containerId($customContainerId)->toHtml();
        $this->assertStringContainsString('<div id="' . $customContainerId, $html);
    }

    public function testSetNoComponentId()
    {
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString('for="file-name"', $html);
        $this->assertStringContainsString('<input id="file-name"', $html);
    }

    public function testSetComponentId()
    {
        $customComponentId = 'test-custom-component-id';
        $html = bsFile()->name('name')->componentId($customComponentId)->toHtml();
        $this->assertStringContainsString('for="' . $customComponentId . '"', $html);
        $this->assertStringContainsString('<input id="' . $customComponentId . '"', $html);
    }

    public function testConfigComponentClass()
    {
        $configComponentClasses = 'test-config-class-component';
        config()->set('bootstrap-components.form.file.class.component', [$configComponentClasses]);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString(
            'class="custom-file-input form-control file-name-component ' . $configComponentClasses . '"',
            $html
        );
    }

    public function testSetComponentClass()
    {
        $configComponentClasses = 'test-config-class-component';
        $customComponentClasses = 'test-custom-class-component';
        config()->set('bootstrap-components.form.file.class.component', [$customComponentClasses]);
        $html = bsFile()->name('name')->componentClasses([$customComponentClasses])->toHtml();
        $this->assertStringContainsString(
            'class="custom-file-input form-control file-name-component ' . $customComponentClasses . '"',
            $html
        );
        $this->assertStringNotContainsString(
            'class="custom-file-input form-control file-name-component ' . $configComponentClasses . '"',
            $html
        );
    }

    public function testConfigContainerHtmlAttributes()
    {
        $configContainerAttributes = 'test-config-attributes-container';
        config()->set('bootstrap-components.form.file.htmlAttributes.container', [$configContainerAttributes]);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString($configContainerAttributes, $html);
    }

    public function testSetContainerHtmlAttributes()
    {
        $configContainerAttributes = 'test-config-attributes-container';
        $customContainerAttributes = 'test-custom-attributes-container';
        config()->set('bootstrap-components.form.file.htmlAttributes.container', [$configContainerAttributes]);
        $html = bsFile()->name('name')->containerHtmlAttributes([$customContainerAttributes])->toHtml();
        $this->assertStringContainsString($customContainerAttributes, $html);
        $this->assertStringNotContainsString($configContainerAttributes, $html);
    }

    public function testConfigComponentHtmlAttributes()
    {
        $configComponentAttributes = 'test-config-attributes-component';
        config()->set('bootstrap-components.form.file.htmlAttributes.component', [$configComponentAttributes]);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringContainsString($configComponentAttributes, $html);
    }

    public function testSetComponentHtmlAttributes()
    {
        $configComponentAttributes = 'test-config-attributes-component';
        $customComponentAttributes = 'test-custom-attributes-component';
        config()->set('bootstrap-components.form.file.htmlAttributes.component', [$configComponentAttributes]);
        $html = bsFile()->name('name')->componentHtmlAttributes([$customComponentAttributes])->toHtml();
        $this->assertStringContainsString($customComponentAttributes, $html);
        $this->assertStringNotContainsString($configComponentAttributes, $html);
    }

    public function testUploadedfileUpload()
    {
        $html = bsFile()->name('name')->uploadedFile(function () {
            return 'Uploaded file !';
        })->toHtml();
        $this->assertStringContainsString('Uploaded file !', $html);
    }

    public function testConfigShowRemoveCheckboxWithUploadedFile()
    {
        config()->set('bootstrap-components.form.file.show_remove_checkbox', true);
        $html = bsFile()->name('name')->uploadedFile(function () {
            return 'html';
        })->toHtml();
        $this->assertStringContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringContainsString('name="remove_name"', $html);
        $this->assertStringContainsString('for="checkbox-remove-name">'
            . __('bootstrap-components::bootstrap-components.label.remove')
            . ' validation.attributes.name', $html);
    }

    public function testConfigShowRemoveCheckboxWithEmptyUploadedFile()
    {
        config()->set('bootstrap-components.form.file.show_remove_checkbox', true);
        $html = bsFile()->name('name')->uploadedFile(function () {
            return null;
        })->toHtml();
        $this->assertStringNotContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringNotContainsString('name="remove_name"', $html);
    }

    public function testConfigShowRemoveCheckboxWithoutUploadedFile()
    {
        config()->set('bootstrap-components.form.file.show_remove_checkbox', true);
        $html = bsFile()->name('name')->toHtml();
        $this->assertStringNotContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringNotContainsString('name="remove_name"', $html);
    }

    public function testConfigHideRemoveCheckboxWithUploadedFile()
    {
        config()->set('bootstrap-components.form.file.show_remove_checkbox', false);
        $html = bsFile()->name('name')->uploadedFile(function () {
            return 'html';
        })->toHtml();
        $this->assertStringNotContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringNotContainsString('name="remove_name"', $html);
    }

    public function testShowRemoveCheckboxWithUploadedFile()
    {
        config()->set('bootstrap-components.form.file.show_remove_checkbox', false);
        $html = bsFile()->name('name')->uploadedFile(function () {
            return 'html';
        })->showRemoveCheckbox(true)->toHtml();
        $this->assertStringContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringContainsString('for="checkbox-remove-name">'
            . __('bootstrap-components::bootstrap-components.label.remove')
            . ' validation.attributes.name', $html);
    }

    public function testHideRemoveCheckbox()
    {
        config()->set('bootstrap-components.form.file.show_remove_checkbox', true);
        $html = bsFile()->name('name')->uploadedFile(function () {
            return 'html';
        })->showRemoveCheckbox(false)->toHtml();
        $this->assertStringNotContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringNotContainsString('name="remove_name"', $html);
    }

    public function testShowRemoveCheckboxWithCustomRemoveLabel()
    {
        config()->set('bootstrap-components.form.file.show_remove_checkbox', false);
        $html = bsFile()->name('name')->uploadedFile(function () {
            return 'html';
        })->showRemoveCheckbox(true, 'Test')->toHtml();
        $this->assertStringContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringContainsString('for="checkbox-remove-name">Test', $html);
    }
}
