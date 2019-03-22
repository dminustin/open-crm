<?php
ini_set('display_errors',1);

// set a constant that holds the project's folder path, like "/var/www/".
// DIRECTORY_SEPARATOR adds a slash to the end of the path
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
// set a constant that holds the project's "application" folder, like "/var/www/application".
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);

chdir(ROOT);

require_once(APP . '/Core/Helpers.php');


// This is the auto-loader for Composer-dependencies (to load tools into your project).
require_once ROOT . 'vendor/autoload.php';


