<?php
/**
 * FileSystem file storage class
 * Implements AbstractFileStorage
 */

namespace OpenCRM\Model\FileStorageFS;


use Klein\App;
use OpenCRM\Core\AbstractFileStorage;
use OpenCRM\Core\Application;
use OpenCRM\Exception\CommonException;
use OpenCRM\Exception\InvalidArgumentException;
use OpenCRM\Model\FileStorageFS\FileDataFS;

class FileStorageFS extends AbstractFileStorage
{

    public function readFile($fileData)
    {
        $fname = $this->generateFilePath($fileData) . $this->generateFileName($fileData);
        if (!file_exists($fname)) {
            throw new CommonException("File not found");
        }
        return file_get_contents($fname);
    }

    public function deleteFile($fileData)
    {
        // TODO: Implement deleteFile() method.
    }

    public function downloadFile($fileData, $filePath)
    {
        // TODO: Implement downloadFile() method.
    }

    public function getFileUrl($fileData)
    {
        if (empty($fileData->ID)) {
            return "";
        }
        $url = Application::app()->config['ATTACHMENTS_PREFIX'];
        $salt1 = Application::app()->config['ATTACHMENTS_SALT1'];
        $salt2 = Application::app()->config['ATTACHMENTS_SALT2'];
        $data = base64_encode($fileData);
        return $url . '?id=' . $fileData->ID . '&data=' . $data . '&hash=' . FileDataFS::calculateFileHash($salt1, $salt2, $data);
    }

    public function saveFileContent($fileData, $fileContent)
    {
        // TODO: Implement saveFileContent() method.
    }

    /**
     * @param string $fileName
     * @param \OpenCRM\Core\AbstractFileData|FileDataFS $fileData
     * @return bool|mixed
     * @throws CommonException
     */
    public function uploadFile($fileName, $fileData)
    {
        $res = $fileData->saveData();
        if (!$res) {
            return false;
        }

        $path = $this->generateFilePath($fileData);
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new CommonException("Could not create dir {$path}");
            }
            chmod($path,0777);
        }

        $fname = $path . $this->generateFileName($fileData);


        if (is_uploaded_file($fileName)) {
            $res = move_uploaded_file($fileName, $fname);
        } else {
            $res = copy($fileName, $fname);
        }

        return $res;


    }

    /**
     * Returns real file path
     * @param $fileData FileDataFS
     * @return string
     */
    protected function generateFilePath($fileData)
    {
        $id = md5(Application::app()->config['COMMON_SALT'] . $fileData->ID);
        return ROOT . "data/uploads/" . substr($id,0, 3) . "/";
    }

    /**
     * Returns real file name
     * @param $fileData FileDataFS
     * @return string
     */
    protected function generateFileName($fileData)
    {
        return md5(Application::app()->config['COMMON_SALT'] . $fileData->ID) . ".dat";
    }


}