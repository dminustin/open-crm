<?php

class Migration_2019042000002 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $res = db()->exec("CREATE TABLE `documents` ( 
          `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
          `file_id` BIGINT UNSIGNED NOT NULL , 
          `title` VARCHAR(255) NOT NULL , 
          `description` VARCHAR(4096) NOT NULL DEFAULT '', 
          `io_type` VARCHAR(64) NOT NULL , 
          `contact_id` BIGINT UNSIGNED NOT NULL , 
          `added_by` BIGINT UNSIGNED NOT NULL , 
          `created_at` DATETIME NOT NULL , 
          PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        return $res;
    }
}