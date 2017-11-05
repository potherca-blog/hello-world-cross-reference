<?php

namespace Potherca\CrossReference\HelloWorld\Model;

class Language
{
    private $names = [];
    private $slug;

    final public function getNames()
    {
        return $this->names;
    }

    final public function getProviders()
    {
        return array_keys($this->names);
    }

    final public function getSlug()
    {
        return $this->slug;
    }

    final public function __construct($slug)
    {
        $this->slug = $slug;
    }

    final public function addName($name, $provider)
    {
        $this->names[$provider] = $name;
    }
}

/*EOF*/
