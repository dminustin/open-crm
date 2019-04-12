<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 22:20
 */

namespace OpenCRM\Controller\Ajax\Contacts;


use OpenCRM\Core\AjaxController;
use OpenCRM\Model\ContactsModel;

class AjaxList extends AjaxController
{
    public static function run()
    {
        $result = ContactsModel::getContacts($_POST);
        foreach($result as $i=>$row) {

            $result[$i] = array_values($row);
        }
        static::response(true, (!$result) ? json_encode($_POST) : 'Contact added successfully', $result);
    }
}