<?php

use \OpenCRM\Model\UserModel;

class Migration_2019032200002 extends \OpenCRM\Core\Migration
{
    public static function run()
    {
        $result = \OpenCRM\Model\CategoryModel::addNewCategory("Default", "Default category");
        $result = $result && \OpenCRM\Model\CategoryModel::addNewCategory("Clients", "Clients");
        $result = $result && \OpenCRM\Model\CategoryModel::addNewCategory("Other", "Other category");
        return $result;
    }
}