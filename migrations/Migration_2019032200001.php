<?php

use \OpenCRM\Model\UserModel;

class Migration_2019032200001 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $db = db();


        $qu = [];
        $qu[] = <<<ZZZ
        CREATE TABLE `categories` (
    `id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ZZZ;

        $qu[] = <<<ZZZ
CREATE TABLE `contacts` (
    `id` bigint(20) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description_short` varchar(2048) NOT NULL,
  `description_full` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ZZZ;


        $qu[] = <<<ZZZ
CREATE TABLE `contacts_tags` (
    `contact_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ZZZ;
        $qu[] = <<<ZZZ
CREATE TABLE `contact_categories` (
    `contact_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ZZZ;
        $qu[] = <<<ZZZ
CREATE TABLE `list_roles` (
    `role_id` int(11) NOT NULL,
  `role_name` varchar(64) NOT NULL,
  `rights` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ZZZ;

        $qu[] = <<<ZZZ
CREATE TABLE `tags` (
    `tag_id` bigint(20) UNSIGNED NOT NULL,
  `tag_name` varchar(128) NOT NULL,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ZZZ;
        $qu[] = <<<ZZZ
CREATE TABLE `users` (
    `id` bigint(20) UNSIGNED NOT NULL,
  `login` varchar(128) NOT NULL,
  `password` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT '1',
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `contacts_tags`
  ADD PRIMARY KEY (`contact_id`,`tag_id`),
  ADD KEY `tag_id_tag` (`tag_id`);
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `contact_categories`
  ADD PRIMARY KEY (`contact_id`,`category_id`),
  ADD KEY `contact_categories_ibfk_2` (`category_id`);
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `list_roles`
  ADD PRIMARY KEY (`role_id`);
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `list_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `tags`
  MODIFY `tag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ZZZ;
        $qu[] = <<<ZZZ
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ZZZ;
        $db->beginTransaction();
        foreach ($qu as $sql) {
            $db->exec($sql);
        }

        $db->exec("INSERT INTO `list_roles` (`role_id`, `role_name`, `rights`) VALUES ('1', 'admin', '{\"rights\": \"all\"}')");

        UserModel::addUser('admin', 'admin', 'Administrator', UserModel::USER_ROLE_admin);
        $db->commit();
        return true;
    }
}