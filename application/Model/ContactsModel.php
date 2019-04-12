<?php
/**
 * Contacts model
 */

namespace OpenCRM\Model;


use OpenCRM\Core\PrepareInputValues;
use OpenCRM\Exception\InvalidArgumentException;
use OpenCRM\Exception\SqlErrorException;

class ContactsModel
{
    static $prepare_fields = [
        'display_name'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'phone'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'email'=>PrepareInputValues::PIV_EMAIL,
        //'category'=>PrepareInputValues::PIV_NUM,
        'description_short'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'description_full'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
    ];

    /**
     * We have to check this fields
     * @var array
     */
    static $required_fileds = [
        'display_name'
    ];


    static function getContacts($post) {
        $res = db()->query('select * from contacts');
        return $res->fetchAll();
    }


    /**
     * @param $data
     * @throws InvalidArgumentException
     * @throws SqlErrorException
     * @return int
     */
    static function addContact($data) {
        $sqls = [];


        db()->beginTransaction();
        $tags = [];
        if (!empty($data['tags']) && is_array($data['tags'])) {
            $tags = TagsModel::addTags($data['tags']);
        }
        unset($data['tags']);

        $categories = [];
        $data['category'] = explode(',', $data['category']);
        if (!empty($data['category']) && is_array($data['category'])) {
            foreach($data['category'] as $cat) {
                if (intval($cat)) {
                    $categories[] = intval($cat);
                }
            }
        }
        unset($data['category']);


        foreach (static::$prepare_fields as $field=>$type) {
            $val = isset($data[$field]) ? $data[$field] : null;
            if (empty($val) && !in_array($field, static::$required_fileds)) {
                $sqls[$field] = "";
                continue;
            }
            $val = PrepareInputValues::escapeTheInput($val, $type);
            if (empty($val) && in_array($field, static::$required_fileds)) {
                throw new InvalidArgumentException("Empty value for ${field} not allowed");
            }
            $sqls[] = "$field = '$val'";
        }


        $sql = "insert into contacts set " . join(", ", $sqls);
        $res = db()->exec($sql);
        if (!$res) {
            throw new SqlErrorException(db()->errorInfo());
        }

        $new_contact_id = db()->lastInsertId();

        TagsModel::addContactsTags($new_contact_id, array_values($tags));
        CategoryModel::setContactCategories($new_contact_id, $categories);

        db()->commit();
        return $new_contact_id;

    }


}