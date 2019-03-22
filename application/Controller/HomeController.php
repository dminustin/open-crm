<?php

namespace OpenCRM\Controller;


class HomeController
{
    /**
     * Start page
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function indexPage()
    {
        echo render('index.html.twig');
    }

    /**
     * Error 404
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function e404()
    {
        echo render('e404.html.twig');
    }

}
