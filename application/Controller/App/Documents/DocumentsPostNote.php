<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 23:10
 */

namespace OpenCRM\Controller\App\Documents;


use OpenCRM\Core\AppController;
use OpenCRM\Core\Application;
use OpenCRM\Model\ContactsModel;
use OpenCRM\Model\DocumentsModel;
use OpenCRM\Model\UserModel;


class DocumentsPostNote extends AppController
{


    public static function run()
    {
        $_POST['added_by'] = currentUser()['id'];
        DocumentsModel::writeNewNote($_POST);
        Application::app()->redirect('/app/documents/write');
    }
}