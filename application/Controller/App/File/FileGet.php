<?php

namespace OpenCRM\Controller\App\File;


use OpenCRM\Core\AppController;
use OpenCRM\Core\Application;
use OpenCRM\Exception\SecurityViolationException;
use OpenCRM\Model\FileStorageFS\FileDataFS;
use OpenCRM\Model\FileStorageFS\FileStorageFS;

class FileGet extends AppController
{
    public static function run()
    {

        $data = $_GET['data'];
        $hash = $_GET['hash'];
        $id = intval($_GET['id']);

        $check = FileDataFS::calculateFileHash(
            Application::app()->config['ATTACHMENTS_SALT1'],
            Application::app()->config['ATTACHMENTS_SALT2'],
            $data
        );
        $data = json_decode(base64_decode($data), true);
        if ($check != $hash) {
            throw new SecurityViolationException("Invalid file hash!");
        }
        if ($data['ID'] != $id) {
            throw new SecurityViolationException("Invalid file ID!");
        }
        $filedata = FileDataFS::createByArray($data);
        header('Content-type: ' . $filedata->mimeType);
        echo (new FileStorageFS())->readFile($filedata);
        exit(0);

    }
}