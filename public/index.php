<?php
require_once('../loader.php');

//Start the session
session_start();

// start the application
\OpenCRM\Core\Application::app()->run();
