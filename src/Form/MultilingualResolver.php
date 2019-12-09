<?php

namespace Okipa\LaravelBootstrapComponents\Form;

class MultilingualResolver
{
    /*
     * The language locales to handle for multilingual components.
     *
     * @property array $locales
     */
    protected $locales = [];

    /**
     * Get the default language locales to handle for multilingual components.
     *
     * @return array
     */
    public function getDefaultLocales(): array
    {
        return $this->locales;
    }

    /**
     * Resolve the multilingual component name.
     *
     * @param string $name
     * @param string $locale
     *
     * @return string
     */
    public function resolveLocalizedName(string $name, string $locale): string
    {
        return $name . '[' . $locale . ']';
    }

    /**
     * Resolve the multilingual component html identifier.
     *
     * @param string $type
     * @param string $name
     *
     * @param string $locale
     * @return string
     */
    public function resolveHtmlIdentifier(string $type, string $name, string $locale): string
    {
        return $type . '-' . $name . '-' . $locale;
    }

    /**
     * Resolve the multilingual component error message.
     *
     * @param string $name
     * @param string $locale
     *
     * @return string|null
     */
    public function resolveErrorMessage(string $name, string $locale): ?string
    {
        $errorMessageBagKey = $name . '.' . $locale;
        $errorMessage = optional(session()->get('errors'))->first($errorMessageBagKey);

        return $errorMessage
            ? str_replace(
                $errorMessageBagKey,
                __('validation.attributes.' . $name) . ' (' . strtoupper($locale) . ')',
                $errorMessage
            )
            : null;
    }
}
