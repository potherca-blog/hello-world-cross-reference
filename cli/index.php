<?php

namespace Potherca\CrossReference\HelloWorld;

$projectPath = dirname(__DIR__);

require $projectPath.'/src/bootstrap.php';

// For listings of ALL languages, see https://en.wikipedia.org/wiki/Lists_of_programming_languages

$providers = [
    Provider\HelloworldcollectionProvider::class,
    Provider\Knightking100Provider::class,
    Provider\Leachim6Provider::class,
];

$cachFile = $projectPath.'/.cache/languages-cache.php';

$fetcher = new Fetcher($providers, $cachFile);

$languages = $fetcher->fetch();

$content = new Printer\ShellPrinter($languages);

echo $content;

/*EOF*/
