<?php
/**
 * Syntactic sugar
 * For most common functions, getters, setters, etc
 */

use OpenCRM\Core\Application;

/**
 * Get config value
 * @param $key
 * @param null $default
 * @return mixed|null
 */
function config($key, $default=null) {
    return (isset(Application::app()->config[$key])) ? Application::app()->config[$key] : $default;
}

/**
 * @return \PDO
 */
function db() {
    return Application::app()->db;
}


/**
 * @param $template
 * @param array $vars
 * @return string
 * @throws \Twig\Error\LoaderError
 * @throws \Twig\Error\RuntimeError
 * @throws \Twig\Error\SyntaxError
 */
function render($template, $vars=[]) {
    return Application::app()->render($template, $vars);
}