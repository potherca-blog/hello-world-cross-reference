<?php

namespace Potherca\CrossReference\HelloWorld\Printer;

use Potherca\CrossReference\HelloWorld\Model\Language;

class ShellPrinter extends AbstractPrinter
{
    final public function output()
    {
        $output = '';

        $languages = $this->getLanguages();

        // $length = max(array_map('strlen', array_keys($languages)));
$length = 40;
        array_walk($languages, function (Language $language) use (&$output, $length) {
            $names = $language->getNames();
            $output .= vsprintf('%-'.$length.'s => %s %s', [array_shift($names), implode(', ', $language->getProviders()), PHP_EOL]);
        });

        return $output;
    }
}
