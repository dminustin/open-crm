<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 23:10
 */

namespace OpenCRM\Controller\App\Dashboard;


use OpenCRM\Core\AppController;

class DashboardView extends AppController
{
    public static function run()
    {
        render('app/dashboard.html.twig');
    }
}