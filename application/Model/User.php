<?php

/**
 * Class user
 *
 */

namespace OpenCRM\Model;

use OpenCRM\Core\Model;

class User extends Model
{
    static function hashPass($pass) {
        return md5(md5($pass . config('PASS_SALT1')) . config('PASS_SALT2'));
    }

    static function addUser() {

    }

}
