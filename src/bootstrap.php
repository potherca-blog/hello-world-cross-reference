<?php

namespace Potherca\CrossReference\HelloWorld;

use Github\Client;
use Github\HttpClient\CachedHttpClient as CachedClient;

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

$languages = fetch_languages_cached($providers, $projectPath.'/src/language-cache.php');

return $languages;

function fetch_languages(array $providers)
{
    $languages = [];

    $client = new Client(new CachedClient(array('cache_dir' => __DIR__.'/.cache')));
    $client->authenticate(getenv('GITHUB_TOKEN'), null, \Github\Client::AUTH_URL_TOKEN);

    array_walk($providers, function ($provider) use (&$languages, $client) {
        if (class_exists($provider) === false) {
            throw new \InvalidArgumentException('Class does not exists: '.$provider);
        }

        $provider = new $provider($client);

        $result = $provider();
        $result = array_change_key_case($result);

        $languages = array_merge_recursive($languages, $result);
        ksort($languages);
    });

    return $languages;
}

function fetch_languages_cached($providers, $cachFile)
{
    if (file_exists($cachFile)) {
        $languages = include $cachFile;
    } else {
        $languages = fetch_languages($providers);
        file_put_contents($cachFile, new Printer\PhpPrinter($languages));
    }

    return $languages;
}
