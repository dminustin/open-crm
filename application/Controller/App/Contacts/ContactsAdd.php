<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 23:10
 */

namespace OpenCRM\Controller\App\Contacts;


use OpenCRM\Core\AppController;
use OpenCRM\Model\CategoryModel;

class ContactsAdd extends AppController
{
    public static function run()
    {
        $categories = CategoryModel::getCategories();
        render('app/contacts/add.html.twig', ['categories'=>$categories]);
    }
}