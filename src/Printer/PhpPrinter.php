<?php

namespace Potherca\CrossReference\HelloWorld\Printer;

class PhpPrinter extends AbstractPrinter
{
    final public function output()
    {
        return vsprintf('<?php return %s;', [var_export($this->getLanguages(), true)]);
    }
}
