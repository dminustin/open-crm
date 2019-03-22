<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 23.03.19
 * Time: 0:32
 */

namespace OpenCRM\Core;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Cache
{
    protected static $cache;
    static public function getInstance($cacheType) {

        if (empty(static::$cache)) {
            if ($cacheType == 'file') {
                static::$cache = new FilesystemAdapter('', 14400, ROOT . 'data/cache/');
            }

        }

        return static::$cache;

    }


}