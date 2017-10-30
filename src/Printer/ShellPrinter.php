<?php

namespace Potherca\CrossReference\HelloWorld\Printer;

class ShellPrinter extends AbstractPrinter
{
    final public function output()
    {
        $output = '';

        $languages = $this->getLanguages();

        $length = max(array_map('strlen', array_keys($languages)));

        array_walk($languages, function ($sources, $language) use (&$output, $length) {
            $output .= vsprintf('%-'.$length.'s => %s %s', [$language, implode(', ', $sources), PHP_EOL]);
        });

        return $output;
    }
}
