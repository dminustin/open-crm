<?php

/**
 * Class user
 *
 */

namespace OpenCRM\Model;

use OpenCRM\Core\Model;
use OpenCRM\Core\PrepareInputValues;
use OpenCRM\Exception\SqlErrorException;

class TagsModel extends Model
{
    static function addTags($list = []) {
        $tmp = [];
        foreach ($list as $tag) {
            $tmp[] = PrepareInputValues::escapeTheInput($tag, PrepareInputValues::PIV_AL_NUM_SYMBOLS);
        }
        $result = [];
        $list = array_unique($tmp);

        foreach ($list as $tag) {
            $result[$tag] = static::addTag($tag);
        }

        return $result;
    }

    static function addTag($tag) {
        $res = ($db = db())->query("select tag_id from tags where tag_name='${tag}'");
        if ($res->rowCount()) {
            return $res->fetch()['tag_id'];
        }
        if ($db->exec("insert into tags set tag_name='${tag}'")) {
            return $db->lastInsertId();
        }
        throw new SqlErrorException($db->errorInfo());
    }


    static function clearContactsTags($user_id)
    {
        db()->exec('delete from contacts_tags where contact_id=' . $user_id);
    }

    static function addContactsTags($user_id, $tag_ids) {
        static::clearContactsTags($user_id);
        $sql = [];
        foreach ($tag_ids as $id) {
            $sql[] = "({$user_id}, {$id})";
        }
        db()->exec('insert into contacts_tags (contact_id, tag_id) values ' . join(', ', $sql));
    }

}
