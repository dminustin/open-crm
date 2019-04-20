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
use OpenCRM\Model\DocumentsModel;


class DocumentsAdd extends AppController
{

    public static function run()
    {
        $contacts = ContactsModel::getContacts();
        $types = DocumentsModel::$DTYPES;
        render('app/documents/add.html.twig', ['contacts'=>$contacts, 'types'=>$types]);
    }
}