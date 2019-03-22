<?php

/**
 * Class user
 *
 */

namespace OpenCRM\Model;

use OpenCRM\Core\Model;

class UserModel extends Model
{
    const USER_ROLE_admin = 1;
    const USER_ROLE_guest = 0;

    static $USER_ROLES = [
        self::USER_ROLE_admin=>'Admin',
        self::USER_ROLE_guest=>'Guest',
        ];

    /**
     * Hashes password with SALT1 and SALT2 from config
     * @param $pass
     * @return string
     */
    static function hashPass($pass) {
        return md5(md5($pass . config('PASS_SALT1')) . config('PASS_SALT2'));
    }


    static function login($login, $password) {
        $res = db()->query('select u.* from users u join 
        list_roles lr on lr.role_id=u.role_id
        where login="' . $login . '"
        and password="' . static::hashPass($password) . '"');
        if ($res->rowCount()==0) {
            return false;
        }

        $_SESSION['userdata'] = $res->fetch();
        return true;
    }


    /**
     * Add new user
     * @param $login
     * @param $password
     * @param $name
     * @param int $role
     * @return bool|string
     */
    static function addUser($login, $password, $name, $role = self::USER_ROLE_guest) {
        $res = db()->exec('INSERT INTO users set name="'.$name.'", login="'.$login.'", password="'.static::hashPass($password).'", role_id=' . $role);
        if ($res) {
            return db()->lastInsertId();
        } else {
            return false;
        }
    }

}
