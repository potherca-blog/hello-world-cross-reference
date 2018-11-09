<?php

namespace Potherca\CrossReference\HelloWorld;

use Github\Client;
use Github\HttpClient\CachedHttpClient as CachedClient;
use Potherca\CrossReference\HelloWorld\Model\Language;

class Fetcher
{
    /** @var string */
    private $cachFile;
    /** @var provider */
    private $providers;

    final public function __construct($providers, $cachFile)
    {
        $this->cachFile = $cachFile;
        $this->providers = $providers;
    }

    final public function fetch()
    {
        $cachFile = $this->cachFile;
        $providers = $this->providers;

        if (file_exists($cachFile)) {
            $content = file_get_contents($cachFile);
            $languages = unserialize($content);
        } else {
            $languages = $this->fetch_languages($providers, dirname($cachFile));
            $content = serialize($languages);
            file_put_contents($cachFile, $content);
        }

        return $languages;
    }

    private function fetch_languages(array $providers, $cachDirectory)
    {
        $languages = [];

        $client = new Client(new CachedClient(array('cache_dir' => $cachDirectory)));
        $client->authenticate(getenv('GITHUB_TOKEN'), null, \Github\Client::AUTH_URL_TOKEN);

        array_walk($providers, function ($provider) use (&$languages, $client) {
            if (class_exists($provider) === false) {
                throw new \InvalidArgumentException('Class does not exists: '.$provider);
            }

            $provider = new $provider($client);

            $longList = $provider();

            array_walk($longList, function ($language) use (&$languages, $provider) {
                $slug = $this->createSlug($language);

                if (array_key_exists($slug, $languages) === false) {
                    $languages[$slug] = new Language($slug);
                }

                $languages[$slug]->addName($language, $provider->getName());
            });
        });

        usort($languages, function (Language $a, Language $b) {
            return strcmp($a->getSlug(), $b->getSlug());
        });

        return $languages;
    }

    private function createSlug($language)
    {
        $string = $language;

        setlocale(LC_ALL, "en_US.utf8");

        /* Replace diacretics with plain counterparts */
        $accents = '/&([A-Za-z]{1,2})(grave|acute|circ|cedil|uml|lig);/';
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace('/&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);/i', '$1', $string);

        $string = strtolower($string);

        /* Strip out anything non-alphanumeric */
        $ignore = ['brainfuck--', 'v--'];
        if (in_array($string, $ignore) === false) {
            $string = str_replace('♯', '#', $string);
            $string = preg_replace(array('/[^0-9a-z+#*]/i', '/nbsp/'), '', $string);
        }


        return $string;

        // return preg_replace($accents, '$1', htmlentities($language,ENT_NOQUOTES,'UTF-8'));
        // return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $language);
    }

}