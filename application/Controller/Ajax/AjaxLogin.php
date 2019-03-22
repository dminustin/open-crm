<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 22:20
 */

namespace OpenCRM\Controller\Ajax;


use OpenCRM\Core\AjaxController;
use OpenCRM\Model\UserModel;

class AjaxLogin extends AjaxController
{
    public static function run()
    {
        $login = getPostVar('login', true);
        $password = getPostVar('password');
        if (empty($login) || empty($password)) {
            static::response(false, 'Pass or Login could not be empty');
        }

        $result = UserModel::login($login, $password);
        static::response($result, (!$result) ? 'Login or password is incorrect or unknown' : 'Welcome back!');
    }
}