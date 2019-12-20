<?php

namespace Okipa\LaravelBootstrapComponents\Tests\Unit\Form\Abstracts;

use Okipa\LaravelBootstrapComponents\Form\Abstracts\FileAbstract;

abstract class InputFileTestAbstract extends InputTestAbstract
{
    public function testInstance()
    {
        $this->assertInstanceOf(FileAbstract::class, $this->getComponent());
    }

    public function testModelValue()
    {
        $user = $this->createUniqueUser();
        $html = $this->getComponent()->model($user)->name('name')->toHtml();
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="' . $this->getComponentType() . '-name">' . $user->name . '</label>',
            $html
        );
    }

    public function testSetValue()
    {
        $customValue = 'test-custom-value';
        $html = $this->getComponent()->name('name')->value($customValue)->toHtml();
        $this->assertStringContainsString(
            '<label class="custom-file-label" for="' . $this->getComponentType() . '-name">' . $customValue
            . '</label>',
            $html
        );
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
        $this->assertStringContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . $oldValue . '</label>', $html);
        $this->assertStringNotContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . $customValue . '</label>', $html);
    }

    public function testSetPlaceholder()
    {
        $placeholder = 'test-custom-placeholder';
        $html = $this->getComponent()->name('name')->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString('custom-file-label', $html);
        $this->assertStringContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . $placeholder . '</label>', $html);
    }

    public function testSetTranslatedPlaceholder()
    {
        $placeholder = 'bootstrap-components::bootstrap-components.label.validate';
        $html = $this->getComponent()->name('name')->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . __($placeholder) . '</label>', $html);
    }

    public function testSetPlaceholderWithLabel()
    {
        $label = 'test-custom-label';
        $placeholder = 'test-custom-placeholder';
        $html = $this->getComponent()->name('name')->label($label)->placeholder($placeholder)->toHtml();
        $this->assertStringContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . __($placeholder) . '</label>', $html);
    }

    public function testNoPlaceholderWithNoLabel()
    {
        $html = $this->getComponent()->name('name')->label(false)->toHtml();
        $this->assertStringContainsString('custom-file-label', $html);
        $this->assertStringContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . __('bootstrap-components::bootstrap-components.label.file') . '</label>', $html);
    }

    public function testNoPlaceholder()
    {
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('custom-file-label', $html);
        $this->assertStringContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . __('bootstrap-components::bootstrap-components.label.file') . '</label>', $html);
    }

    public function testHidePlaceholder()
    {
        $html = $this->getComponent()->name('name')->placeholder(false)->toHtml();
        $this->assertStringNotContainsString('ustom-file-label', $html);
        $this->assertStringNotContainsString('<label class="custom-file-label" for="' . $this->getComponentType()
            . '-name">' . __('bootstrap-components::bootstrap-components.label.file') . '</label>', $html);
    }

    public function testSetCustomComponentClasses()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->toHtml();
        $this->assertStringContainsString('class="component form-control custom-file-input default component classes"', $html);
    }

    public function testSetComponentClassesOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->componentClasses(['custom', 'component', 'classes'])->toHtml();
        $this->assertStringContainsString('class="component form-control custom-file-input custom component classes"', $html);
        $this->assertStringNotContainsString('class="component form-control custom-file-input default component classes"', $html);
    }

    public function testSetUploadedfile()
    {
        $html = $this->getComponent()->name('name')->uploadedFile(function() {
            return 'Uploaded file !';
        })->toHtml();
        $this->assertStringContainsString('Uploaded file !', $html);
    }

    public function testCustomShowRemoveCheckbox()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->uploadedFile(function() {
            return 'html';
        })->toHtml();
        $this->assertStringContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringContainsString(' name="remove_name"', $html);
    }

    public function testSetShowRemoveCheckboxOverridesDefault()
    {
        config()->set(
            'bootstrap-components.form.components.' . $this->getComponentKey(),
            get_class($this->getCustomComponent())
        );
        $html = $this->getComponent()->name('name')->uploadedFile(function() {
            return 'html';
        })->showRemoveCheckbox(false)->toHtml();
        $this->assertStringNotContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringNotContainsString(' name="remove_name"', $html);
    }

    public function testSetShowRemoveCheckboxWithoutUploadedFile()
    {
        $html = $this->getComponent()->name('name')->uploadedFile(function() {
            return null;
        })->showRemoveCheckbox()->toHtml();
        $this->assertStringNotContainsString('<input id="checkbox-remove-name"', $html);
        $this->assertStringNotContainsString(' name="remove_name"', $html);
    }

    public function testDefaultRemoveCheckboxLabel()
    {
        $html = $this->getComponent()->name('name')->uploadedFile(function() {
            return 'html';
        })->showRemoveCheckbox()->toHtml();
        $this->assertStringContainsString(' for="checkbox-remove-name">'
            . __('bootstrap-components::bootstrap-components.label.remove')
            . ' validation.attributes.name', $html);
    }

    public function testSetCustomRemoveCheckboxLabel()
    {
        $html = $this->getComponent()->name('name')->uploadedFile(function() {
            return 'html';
        })->showRemoveCheckbox(true, 'Test')->toHtml();
        $this->assertStringContainsString(' for="checkbox-remove-name">Test', $html);
    }

    public function testSetCustomRemoveCheckboxTranslatedLabel()
    {
        $label = 'bootstrap-components::bootstrap-components.label.validate';
        $html = $this->getComponent()->name('name')->uploadedFile(function() {
            return 'html';
        })->showRemoveCheckbox(true, $label)->toHtml();
        $this->assertStringContainsString(' for="checkbox-remove-name">' . __($label), $html);
    }
}