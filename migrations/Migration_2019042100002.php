<?php

class Migration_2019042100002 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $res = db()->exec("CREATE TABLE `events` ( `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , `owner_id` BIGINT UNSIGNED NOT NULL , `creator_id` BIGINT UNSIGNED NOT NULL , `title` VARCHAR(255) NOT NULL , `description` LONGTEXT NOT NULL , `regdate` DATETIME NOT NULL , `startdate` DATETIME NOT NULL , PRIMARY KEY (`id`), INDEX (`owner_id`), INDEX (`creator_id`), INDEX (`startdate`)) ENGINE = InnoDB;");
        return $res;
    }
}