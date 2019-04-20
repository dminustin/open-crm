<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 21.04.19
 * Time: 1:44
 */

namespace OpenCRM\Controller\Ajax;


use OpenCRM\Core\AjaxController;

class AjaxSysMessages extends AjaxController
{
    public static function run()
    {
        $msg = (!empty($_SESSION['messages'])) ? $_SESSION['messages'] : [];
        $_SESSION['messages'] = [];
        static::response(true, "", $msg);
    }
}