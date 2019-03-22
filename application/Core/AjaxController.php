<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 22:20
 */

namespace OpenCRM\Core;


abstract class AjaxController
{
    static function response($result, $message = '', $data = []) {
        header('Content-type: application/json');
        echo json_encode([
            'result'=> (boolean) $result,
            'message'=>$message,
            'data'=>$data
        ], JSON_UNESCAPED_UNICODE);
        die();
    }

    abstract static function run();
}