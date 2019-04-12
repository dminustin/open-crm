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

class AjaxAdd extends AjaxController
{
    public static function run()
    {
        $result = ContactsModel::addContact($_POST);

        static::response($result, (!$result) ? json_encode($_POST) : 'Contact added successfully');
    }
}