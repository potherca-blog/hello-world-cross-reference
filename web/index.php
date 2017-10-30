<?php

namespace Potherca\CrossReference\HelloWorld;

$languages = require dirname(__DIR__).'/src/bootstrap.php';

$content = new Printer\HtmlPrinter($languages);

echo $content;

/*EOF*/