<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 23:10
 */

namespace OpenCRM\Controller\App\Documents;


use OpenCRM\Core\AppController;
use OpenCRM\Model\ContactsModel;


class DocumentsList extends AppController
{
    public static function run()
    {
        $contacts = ContactsModel::getContacts();
        render('app/documents/add.html.twig', ['contacts'=>$contacts]);
    }
}