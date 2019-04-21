<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 20.04.19
 * Time: 22:50
 */

namespace OpenCRM\Model\FileStorageFS;


use OpenCRM\Core\AbstractFileData;
use OpenCRM\Core\Application;

class FileDataFS extends AbstractFileData
{

    public static function findByID($id)
    {
        // TODO: Implement findByID() method.
    }

    public static function deleteByID($id)
    {
        // TODO: Implement deleteByID() method.
    }

    public function saveData()
    {
        $sql = []; $update = [];
        if (!empty($this->ID)) {
            $sql[] = "id={$this->ID}";
        }
        $update[] = $sql[] = "mime_type='{$this->mimeType}'";
        $update[] = $sql[] = "file_type='{$this->fileType}'";
        $update[] = $sql[] = "file_size='{$this->fileSize}'";
        $update[] = $sql[] = "file_name='{$this->originalName}'";

        $sql[] = "created_at='".date('Y-m-d H:i:s', Application::app()->getAppTime())."'";


        $sql = "INSERT INTO files_data SET " . join(", ", $sql) . " ON DUPLICATE KEY UPDATE " . join(", ", $update);
        $res = db()->exec($sql);
        if ($res !== false) {
            if (empty($this->ID)) {
                $this->ID = db()->lastInsertId();
            }
            return $this->ID;
        }
        return false;
    }
}