<?php

namespace Potherca\CrossReference\HelloWorld\Provider;

abstract class AbstractFetcher
{
    /** @var \Github\Client */
    private $client;

    abstract public function getName();

    final  public function getClient()
    {
        return $this->client;
    }

    final public function __construct(\Github\Client $client)
    {
        $this->client = $client;
    }

    abstract public function fetch();

    final public function __invoke()
    {
        $languages = $this->fetch();

        ksort($languages);

        return $languages;
    }
}
