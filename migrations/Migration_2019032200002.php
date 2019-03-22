<?php

use \OpenCRM\Model\UserModel;

class Migration_2019032200002 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $db = db();

        $db->exec("CREATE TABLE `categories` ( 
            `id` BIGINT NOT NULL AUTO_INCREMENT , 
            `title` VARCHAR(255) NOT NULL , 
            `description` TEXT NOT NULL , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB;");

        $db->exec('CREATE TABLE `contacts` ( 
            `id` BIGINT NOT NULL AUTO_INCREMENT , 
            `display_name` VARCHAR(255) NOT NULL , 
            `phone` VARCHAR(255) NOT NULL , 
            `email` VARCHAR(255) NOT NULL , 
            `description_short` VARCHAR(2048) NOT NULL , 
            `description_full` TEXT NOT NULL , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB;');

        $db->exec('CREATE TABLE `contact_categories` ( 
          `contact_id` BIGINT NOT NULL , 
          `category_id` BIGINT NOT NULL , 
          PRIMARY KEY (`contact_id`, `category_id`)) ENGINE = InnoDB;');

        $db->exec('insert into categories set title="Basic category", description="Basic category"');
        $db->exec('ALTER TABLE `contact_categories` ADD FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;');
        $db->exec('ALTER TABLE `contact_categories` ADD FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;');

        return true;
    }
}