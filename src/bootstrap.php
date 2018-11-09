<?php

namespace Potherca\CrossReference\HelloWorld;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require $projectPath.'/vendor/autoload.php';

/* Load `.env` */
if (is_readable($projectPath . '/.env')) {
  $dotenv = new \Dotenv\Dotenv($projectPath, '.env');
  $dotenv->load();
  unset($dotenv);
}

/*EOF*/