<?php

namespace Potherca\CrossReference\HelloWorld\Printer;

abstract class AbstractPrinter
{
    /** @var array */
    private $languages;

    final public function getLanguages()
    {
        return $this->languages;
    }

    final public function __construct(array $languages)
    {
        ksort($languages);

        $this->languages = $languages;
    }

    final public function __toString()
    {
        return $this->output();
    }

    abstract public function output();
}
