<?php
/**
 * Created by PhpStorm.
 * UserModel: danila
 * Date: 22.03.19
 * Time: 18:02
 */

namespace OpenCRM\Console;


use OpenCRM\Core\Console;

class Install extends Console
{
    public static function run($args = [])
    {

        static::log('Start installation script');


        static::log('Init app');
        //init App
        $app = \OpenCRM\Core\Application::app();

        static::log('Get DB tables list');
        $tables = $app->db->query('show tables')->fetchAll();
        if (!empty($tables)) {
            static::error('DB contains any tables, you cannot continue');
        }

        static::log('Create table migration');
        $app->db->exec("CREATE TABLE `migration` ( `id` VARCHAR(64) NOT NULL , `regdate` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;");


        static::log('Run all migrations');
        //Run all migrations
        Migration::run();


    }
}