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


class DocumentsPost extends AppController
{


    public static function run()
    {
        $_POST['added_by'] = currentUser()['id'];
        DocumentsModel::uploadNewDocuments($_POST, $_FILES);
        Application::app()->redirect('/app/documents/add');
    }
}