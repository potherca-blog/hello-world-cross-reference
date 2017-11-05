<?php

namespace Potherca\CrossReference\HelloWorld\Printer;

use Potherca\CrossReference\HelloWorld\Model\Language;

abstract class AbstractPrinter
{
    /** @var Language[] */
    private $languages;

    /** @return Language[] */
    final public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param Language[] $languages
     */
    final public function __construct(array $languages)
    {
        $this->languages = $languages;
    }

    final public function __toString()
    {
        return $this->output();
    }

    abstract public function output();
}
