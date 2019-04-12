<?php

namespace OpenCRM\Core;


class Model
{
    static function cacheValue($key, $value, $ttl = 1440) {
        $prefix = static::class;
        $addr = md5($prefix) . '_' . $key;
        $item = cache()->getItem($addr);
        //$item->expiresAt($ttl);
        $item->set($value);
        cache()->save($item);
    }

    static function getCachedValue($key, $default = null) {
        $prefix = static::class;
        $addr = md5($prefix) . '_' . $key;
        if (!cache()->hasItem($addr)) {
            return $default;
        }
        return cache()->getItem($key);
    }

    static function invalidateCacheValue($key) {
        $prefix = static::class;
        $addr = md5($prefix) . '_' . $key;
        cache()->deleteItem($addr);
    }

}
