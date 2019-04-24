<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 24.04.19
 * Time: 2:06
 */

namespace OpenCRM\Controller\Ajax\Events;


use OpenCRM\Core\AjaxController;
use OpenCRM\Model\EventsModel;

class AjaxAdd extends AjaxController
{
    public static function run()
    {
        $result = EventsModel::newEmptyEvent($_POST['title'], $_POST['start_date'], $_POST['start_time']);
        static::response(($result!==false), '', ['event_id'=>$result]);
    }
}