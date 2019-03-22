<?php

use \OpenCRM\Model\UserModel;

class Migration_2019032200001 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $db = db();

        $db->exec("CREATE TABLE `list_roles` ( 
          `role_id` INT NOT NULL AUTO_INCREMENT , 
          `role_name` VARCHAR(64) NOT NULL , 
          `rights` JSON NOT NULL , 
          PRIMARY KEY (`role_id`)) ENGINE = InnoDB;");


        $db->exec("CREATE TABLE users ( 
              `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
              `login` VARCHAR(128) NOT NULL , 
              `password` VARCHAR(64) NOT NULL , 
              `name` VARCHAR(128) NOT NULL , 
              `active` SMALLINT(1) NOT NULL DEFAULT '1' , 
              `role_id` INT(11) NOT NULL , 
              PRIMARY KEY (`id`)) ENGINE = InnoDB;");

        $db->exec('ALTER TABLE `users` ADD FOREIGN KEY (`role_id`) REFERENCES `list_roles`(`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;');

        $db->exec('insert into list_roles set role_id=' . UserModel::USER_ROLE_admin . ', role_name="admin", rights=\'{"all": true}\'');
        $db->exec('insert into list_roles set role_id=' . UserModel::USER_ROLE_guest . ', role_name="guest", rights=\'{}\'');


        UserModel::addUser('admin', 'admin', 'Administrator', UserModel::USER_ROLE_admin);
        return true;
    }
}