<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 23:10
 */

namespace OpenCRM\Controller\App;


use OpenCRM\Core\AppController;


class ContactsListClass extends AppController
{
    public static function run()
    {
        render('app/contacts/list.html.twig', []);
    }
}