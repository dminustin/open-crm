<?php

class Migration_2019042000001 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $res = db()->exec("CREATE TABLE `files_data` ( 
          `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
          `mime_type` VARCHAR(64) NOT NULL , 
          `file_type` VARCHAR(64) NOT NULL , 
          `file_size` INT(11) UNSIGNED NOT NULL , 
          `file_name` VARCHAR(255) NOT NULL , 
          `created_at` DATETIME NOT NULL , 
          PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        return $res;
    }
}