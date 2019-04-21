<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 21.04.19
 * Time: 23:44
 */

namespace OpenCRM\Controller\Ajax\Documents;


use OpenCRM\Core\AjaxController;
use OpenCRM\Model\DocumentsModel;

class AjaxList extends AjaxController
{
    public static function run()
    {
        $list = DocumentsModel::getDocumentsList();
        array_walk($list, function (&$a) {
            $a = array_values($a);
        });
        static::response(true, "", $list);
    }
}