<?php

class Migration_2019042100001 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $res = db()->exec("ALTER TABLE `documents` CHANGE `file_id` `file_id` BIGINT(20) UNSIGNED NULL;");
        $res = db()->exec("ALTER TABLE `documents` CHANGE `description` `description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        return $res;
    }
}