<?php

namespace Okipa\LaravelBootstrapComponents\Tests\Dummy\CustomComponents;

use Okipa\LaravelBootstrapComponents\Components\Media\Video;

class CustomVideo extends Video
{
    /**
     * @inheritDoc
     */
    protected function setPoster(): string
    {
        return 'default-poster';
    }

    /**
     * @inheritDoc
     */
    protected function setLegend(): ?string
    {
        return 'default-legend';
    }

    /**
     * @inheritDoc
     */
    protected function setComponentClasses(): array
    {
        return ['default', 'component', 'classes'];
    }

    /**
     * @inheritDoc
     */
    protected function setContainerClasses(): array
    {
        return ['default', 'container', 'classes'];
    }

    /**
     * @inheritDoc
     */
    protected function setComponentHtmlAttributes(): array
    {
        return ['default' => 'component', 'html' => 'attributes'];
    }

    /**
     * @inheritDoc
     */
    protected function setContainerHtmlAttributes(): array
    {
        return ['default' => 'container', 'html' => 'attributes'];
    }
}