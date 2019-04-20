<?php

/**
 * Class user
 *
 */

namespace OpenCRM\Model;

use OpenCRM\Core\Model;

class CategoryModel extends Model
{
    /**
     * Returns list of all categories
     * @return array
     */
    static function getCategories()
    {
        $list = db()->query('select * from categories')->fetchAll();
        return $list;
    }


    /**
     * Adds new category
     * @param $name
     * @param string $description
     * @return bool|string
     */
    static function addNewCategory($name, $description = "")
    {
        $name = quote($name, \OpenCRM\Core\PrepareInputValues::PIV_AL_NUM_SYMBOLS);
        $res = db()->exec("insert into categories set title='{$name}', description='{$description}'");
        if ($res) {
            return db()->lastInsertId();
        } else {
            return false;
        }
    }


    /**
     * Sets contact`s categoriest
     * @param $contact_id
     * @param $categories
     */
    static function setContactCategories($contact_id, $categories)
    {
        db()->exec('delete from contact_categories where contact_id=' . $contact_id);
        $sqls = [];
        foreach ($categories as $cat) {
            $sqls[] = "({$contact_id}, {$cat})";
        }
        if (empty($sqls)) {
            return;
        }
        db()->exec("insert into contact_categories(contact_id, category_id) values " . join(", ", $sqls));

    }

}
