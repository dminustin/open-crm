<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 30.03.19
 * Time: 4:03
 */

namespace OpenCRM\Model;


use OpenCRM\Core\PrepareInputValues;
use OpenCRM\Exception\InvalidArgumentException;

class ContactsModel
{
    static $prepare_fields = [
        'display_name'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'phone'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'email'=>PrepareInputValues::PIV_EMAIL,
        'category'=>PrepareInputValues::PIV_NUM,
        'description_short'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'description_full'=>PrepareInputValues::PIV_AL_NUM_SYMBOLS,
    ];

    /**
     * We have to check this fields
     * @var array
     */
    static $required_fileds = [
        'display_name','category'
    ];

    /**
     * @param $data
     * @throws InvalidArgumentException
     */
    static function addContact($data) {
        $sqls = [];
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
            $sqls[$field] = $val;
        }

        if (!empty($data['tags']) && is_array($data['tags'])) {
            TagsModel::addTags($data['tags']);
        }

    }


}