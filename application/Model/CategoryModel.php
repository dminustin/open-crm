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
        $list = db()->query('select * from categories')->fetchAll();
        return $list;
    }

    static function setContactCategories($contact_id, $categories) {
        db()->exec('delete from contact_categories where contact_id=' . $contact_id);
        $sqls = [];
        foreach($categories as $cat) {
            $sqls[] = "({$contact_id}, {$cat})";
        }
        if (empty($sqls)) {
            return;
        }
        db()->exec("insert into contact_categories(contact_id, category_id) values " . join(", ", $sqls));

    }

}
