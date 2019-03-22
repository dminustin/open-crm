<?php
namespace OpenCRM\Core;


abstract class Migration
{
    /**
     * @return boolean
     */
    abstract static function run();

    public static function error($message) {
        echo render('console/console_error.html.twig', ['message'=>$message]);
    }
}