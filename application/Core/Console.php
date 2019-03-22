<?php

namespace OpenCRM\Core;


abstract class Console
{
    public static function error($message) {
        echo render('console/console_error.html.twig', ['message'=>$message]);
        die();
    }

    public static function log($message) {
        echo $message . PHP_EOL;
    }

    abstract static function run();
}