<?php
/**
 * This class provides file storage abstraction layer
 */

namespace OpenCRM\Core;


abstract class AbstractFileStorage
{
    var $bucket;

    /**
     * Set the current bucket
     * @param $bucket
     * @return $this
     */
    public function bucket($bucket)
    {
        //todo create if not exists
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * create file with content
     * @param $fileData
     * @param $fileContent
     * @return mixed
     */
    abstract public function saveFileContent($fileData, $fileContent);


    /**
     * deletes file from bucket
     * @param $fileData AbstractFileData
     * @return mixed
     */
    abstract public function deleteFile($fileData);

    /**
     * copy file into the bucket
     * @param $fileName string
     * @param $fileData AbstractFileData
     * @return mixed
     */
    abstract public function uploadFile($fileName, $fileData);

    /**
     * copy file from the bucket
     * @param $fileData AbstractFileData
     * @param $filePath string
     * @return mixed
     */
    abstract public function downloadFile($fileData, $filePath);

    /**
     * copy file from the bucket
     * @param $fileData AbstractFileData
     * @return string
     */
    abstract public function readFile($fileData);

    /**
     * outputs: 'http://domain.com/files/f/i/file.txt'
     * @param $fileData AbstractFileData
     * @return string
     */
    abstract public function getFileUrl($fileData);
}