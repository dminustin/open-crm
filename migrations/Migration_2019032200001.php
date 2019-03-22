<?php


class Migration_2019032200001 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $db = db();
        $db->exec("CREATE TABLE users ( 
              `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
              `login` VARCHAR(128) NOT NULL , 
              `password` VARCHAR(64) NOT NULL , 
              `active` SMALLINT(1) NOT NULL DEFAULT '1' , 
              `name` VARCHAR(128) NOT NULL , 
              PRIMARY KEY (`id`)) ENGINE = InnoDB;");

        $db->exec("CREATE TABLE `list_roles` ( 
          `role_id` INT NOT NULL AUTO_INCREMENT , 
          `role_name` VARCHAR(64) NOT NULL , 
          `rights` JSON NOT NULL , 
          PRIMARY KEY (`role_id`)) ENGINE = InnoDB;");


        $db->exec('insert into users set id=1 login="admin" password="", name="CRM Admin"');


        return false;
    }
}