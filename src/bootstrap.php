<?php

namespace Potherca\CrossReference\HelloWorld;

use Github\Client;
use Github\HttpClient\CachedHttpClient as CachedClient;
use Potherca\CrossReference\HelloWorld\Model\Language;

ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

$projectPath = dirname(__DIR__);

require $projectPath.'/vendor/autoload.php';

// =============================================================================
/*/ Grab things from Disk, DB, Request, Environment, etc. /*/
// -----------------------------------------------------------------------------
/* Load `.env` */
if (is_readable($projectPath . '/.env')) {
  $dotenv = new \Dotenv\Dotenv($projectPath, '.env');
  $dotenv->load();
  unset($dotenv);
}

// For listings of ALL languages, see https://en.wikipedia.org/wiki/Lists_of_programming_languages

$providers = [
    Provider\HelloworldcollectionProvider::class,
    Provider\Knightking100Provider::class,
    Provider\Leachim6Provider::class,
];

$languages = fetch_languages_cached($providers, $projectPath.'/.cache/languages-cache.php');

return $languages;

/*EOF*/

function fetch_languages(array $providers, $cachDirectory)
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
            $slug = createSlug($language);

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

function fetch_languages_cached($providers, $cachFile)
{
    if (file_exists($cachFile)) {
        $content = file_get_contents($cachFile);
        $languages = unserialize($content);
    } else {
        $languages = fetch_languages($providers, dirname($cachFile));
        $content = serialize($languages);
        file_put_contents($cachFile, $content);
    }

    return $languages;
}

function createSlug($language)
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
