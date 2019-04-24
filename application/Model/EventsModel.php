<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 24.04.19
 * Time: 2:09
 */

namespace OpenCRM\Model;


use OpenCRM\Core\Model;

class EventsModel extends Model
{
    static function newEmptyEvent($title, $date, $time)
    {
        $title = quote($title, \OpenCRM\Core\PrepareInputValues::PIV_AL_NUM_SYMBOLS);
        $hour = sprintf('%02d', intval($time['h']));
        $minute = sprintf('%02d', intval($time['m']));

        $date = intval($date['y']) . '-' . sprintf('%02d', intval($date['m'])) . '-' . sprintf('%02d', intval($date['d']));

        if (empty($title)) {
            return false;
        }
        $owner = currentUser()['id'];
        $res = db()->exec("insert into events set 
          owner_id={$owner}, 
          creator_id={$owner}, 
          title='{$title}', 
          description='',
          regdate=NOW(),
          startdate='{$date} {$hour}:{$minute}:00'
          ");
        if ($res === false) {
            return false;
        }
        return db()->lastInsertId();
    }



}