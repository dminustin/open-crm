<?php

class Migration_2019033000001 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $db = db();

        $db->exec("CREATE TABLE `tags` ( 
        `tag_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
        `tag_name` VARCHAR(128) NOT NULL , 
        `regdate` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        PRIMARY KEY (`tag_id`)) ENGINE = InnoDB;");

        return true;
    }
}