<?php

namespace Okipa\LaravelBootstrapComponents\Form;

use Closure;
use Exception;
use Illuminate\Support\HtmlString;
use Okipa\LaravelBootstrapComponents\Component;

class File extends Input
{
    /**
     * The component config key.
     *
     * @property string $view
     */
    protected $configKey = 'form.file';

    /**
     * The input type.
     *
     * @property string $type
     */
    protected $type = 'file';

    /**
     * The uploaded file closure.
     *
     * @property Closure $uploadedFile
     */
    protected $uploadedFile;

    /**
     * The show remove file checkbox status.
     *
     * @property bool $showRemoveCheckbox
     */
    protected $showRemoveCheckbox;

    /**
     * The remove-file-checkbox label.
     *
     * @property string $removeCheckboxLabel
     */
    protected $removeCheckboxLabel;

    /**
     * Set the uploaded file closure.
     *
     * @param Closure $uploadedFile
     *
     * @return $this
     */
    public function uploadedFile(Closure $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    /**
     * Show the remove checkbox.
     *
     * @param bool $showRemoveCheckbox
     * @param string|null $removeCheckboxLabel
     *
     * @return $this
     */
    public function showRemoveCheckbox(bool $showRemoveCheckbox = true, string $removeCheckboxLabel = null): self
    {
        $this->showRemoveCheckbox = $showRemoveCheckbox;
        $this->removeCheckboxLabel = $removeCheckboxLabel;

        return $this;
    }

    /**
     * Set the input values.
     *
     * @return array
     * @throws Exception
     */
    protected function getValues(): array
    {
        return array_merge(parent::getValues(), [
            'uploadedFileHtml' => $this->getUploadedFileHtml(),
            'showRemoveCheckbox' => $this->getShowRemoveCheckboxStatus(),
            'removeCheckboxLabel' => $this->getRemoveCheckboxLabel(parent::getValues()['label']),
            'placeholder' => $this->placeholder ?? __('bootstrap-components::bootstrap-components.label.file'),
        ]);
    }

    /**
     * Get the uploadedFile HTML.
     *
     * @return HtmlString
     */
    protected function getUploadedFileHtml(): HtmlString
    {
        $uploadedFileHtml = '';
        $uploadedFile = $this->uploadedFile;
        if ($uploadedFile) {
            if ($uploadedFile() instanceof Component) {
                $uploadedFileHtml = $uploadedFile()->toHtml();
            } else {
                $uploadedFileHtml = $uploadedFile();
            }
        }

        return new HtmlString($uploadedFileHtml);
    }

    /**
     * @return bool
     */
    protected function getShowRemoveCheckboxStatus(): bool
    {
        return $this->showRemoveCheckbox
            ?? config('bootstrap-components.' . $this->configKey . '.showRemoveCheckbox', false);
    }

    /**
     * @param string|null $defaultLabel
     *
     * @return string
     */
    protected function getRemoveCheckboxLabel(?string $defaultLabel): string
    {
        $translatedDefaultLabel = __($defaultLabel);
        $defaultRemoveCheckboxLabel = (string)__('bootstrap-components::bootstrap-components.label.remove')
            . ($translatedDefaultLabel ? ' ' . strtolower($translatedDefaultLabel) : '');

        return $this->removeCheckboxLabel ?? $defaultRemoveCheckboxLabel;
    }
}
