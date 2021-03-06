<?php

namespace Okipa\LaravelBootstrapComponents\Components\Media\Abstracts;

use Okipa\LaravelBootstrapComponents\Components\ComponentAbstract;

abstract class MediaAbstract extends ComponentAbstract
{
    protected ?string $label = null;

    protected ?string $caption = null;

    protected ?string $src = null;

    public function __construct()
    {
        parent::__construct();
        $this->caption = $this->setCaption();
    }

    public function label(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function src(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function caption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    protected function getViewParams(): array
    {
        return array_merge(parent::getViewParams(), [
            'label' => $this->getLabel(),
            'src' => $this->getSrc(),
            'caption' => $this->getCaption(),
        ]);
    }

    protected function getLabel(): ?string
    {
        return (string) __($this->label);
    }

    protected function getSrc(): ?string
    {
        return $this->src;
    }

    protected function getCaption(): ?string
    {
        return (string) __($this->caption);
    }

    abstract protected function setCaption(): ?string;

    protected function checkValuesValidity(): void
    {
        //
    }
}
