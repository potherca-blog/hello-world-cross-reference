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

        setlocale(LC_ALL, "en_US.utf8");

        $accents = '/&([A-Za-z]{1,2})(grave|acute|circ|cedil|uml|lig);/';

        /* Replace diacretics with plain counterparts */
        $languages = array_map(function ($language) use ($accents) {
            $string = htmlentities($language, ENT_QUOTES, 'UTF-8');
            $string = preg_replace('/&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);/i', '$1', $string);
            $string = preg_replace(array('/[^0-9a-z]/i', '/-+/', '/nbsp/', '/ +/'), ' ', $string);
            return trim($string);

            // return preg_replace($accents, '$1', htmlentities($language,ENT_NOQUOTES,'UTF-8'));
            return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $language);
        }, $languages);

        ksort($languages);

        return array_fill_keys($languages, [$this->getName()]);
    }
}
