<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 23:10
 */

namespace OpenCRM\Controller\App\Contacts;


use OpenCRM\Core\AppController;


class ContactsList extends AppController
{
    public static function run()
    {
        render('app/contacts/list.html.twig', []);
    }
}