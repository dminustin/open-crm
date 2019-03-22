<?php

/**
 * Class user
 *
 */

namespace OpenCRM\Model;

use OpenCRM\Core\Model;

class CategoryModel extends Model
{
    static function getCategories() {
        $list = static::getCachedValue('categories');
        if (!empty($list)) {
            return $list;
        }
        $list = db()->query('select * from categories')->fetchAll();
        self::cacheValue('categories', $list);
        return $list;
    }
}
