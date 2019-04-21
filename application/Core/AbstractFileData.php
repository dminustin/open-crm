<?php
/**
 * File Data for File Storage
 */

namespace OpenCRM\Core;


abstract class AbstractFileData
{

    const FTYPE_IMAGE = "image";
    const FTYPE_AUDIO = "audio";
    const FTYPE_ARCHIVE = "archive";
    const FTYPE_TEXT = "text";

    static $mime_types = [
        // texts
        "text/plain" => self::FTYPE_TEXT,

        // images
        "image/png" => self::FTYPE_IMAGE,
        "image/jpeg" => self::FTYPE_IMAGE,
        "image/jpg" => self::FTYPE_IMAGE,
        "image/jp2" => self::FTYPE_IMAGE,
        "image/gif" => self::FTYPE_IMAGE,
        "image/bmp" => self::FTYPE_IMAGE,
        "image/vnd.microsoft.icon" => self::FTYPE_IMAGE,
        "image/tiff" => self::FTYPE_IMAGE,
        "image/svg+xml" => self::FTYPE_IMAGE,

        // archives
        "application/zip" => self::FTYPE_ARCHIVE,
        "application/x-rar-compressed" => self::FTYPE_ARCHIVE,

        // audio/video
        "audio/mpeg" => self::FTYPE_AUDIO,
        "video/quicktime" => self::FTYPE_AUDIO,
    ];


    static $fields = [
        'ID' => 'id',
        'mimeType' => 'mime_type',
        'fileType' => 'file_type',
        'fileSize' => 'file_size',
        'originalName' => 'file_name',
        'createdAt' => 'created_at'
    ];


    var $fileType;
    var $mimeType;
    var $fileSize;
    var $createdAt;
    var $ID;
    var $originalName;

    /**
     * Fills object`s data by file`s data
     * @param $fileName
     * @param $originalName
     * @return AbstractFileData
     */
    public static function fillByRealFile($fileName, $originalName)
    {
        $result = new Static();
        $mime_type = mime_content_type($fileName);
        $result->mimeType($mime_type)
            ->fileSize(filesize($fileName))
            ->fileType(static::getFileTypeByMimeType($mime_type))
            ->originalName($originalName);
        return $result;
    }


    public static function calculateFileHash($salt1, $salt2, $data)
    {
        return md5(md5($data . $salt1) . $salt2);
    }



    /**
     * Sets original name
     * @param $originalName
     * @return $this
     */
    public function originalName($originalName)
    {
        $this->originalName = $originalName;
        return $this;
    }

    /**
     * Sets file type (image, text, archive)
     * @param $fileType
     * @return $this
     */
    public function fileType($fileType)
    {
        $this->fileType = $fileType;
        return $this;
    }

    /**
     * Sets file size
     * @param $fileSize
     * @return $this
     */
    public function fileSize($fileSize)
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    /**
     * Sets mime type (image/jpeg e.t.c)
     * @param $mimeType
     * @return $this
     */
    public function mimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public static function getFileTypeByMimeType($mimeType)
    {
        return (isset(static::$mime_types[$mimeType])) ? static::$mime_types[$mimeType] : "";
    }

    /**
     * Create object from hash array
     * @param $data
     * @return AbstractFileData
     */
    public static function createByArray($data)
    {
        $result = new Static();
        foreach (static::$fields as $internal => $external) {
            if (isset($data[$external])) {
                $result->$internal = $data[$external];
            }else if (isset($data[$internal])) {
                $result->$internal = $data[$internal];
            }
        }

        return $result;
    }

    /**
     * Returns AbstractFileData or null (if not found)
     * @param $id
     * @return AbstractFileData|null
     */
    abstract static public function findByID($id);

    /**
     * Deletes FileData
     * @param $id
     * @return boolean
     */
    abstract static public function deleteByID($id);

    public function __toString()
    {
        $result = [];
        foreach (static::$fields as $k => $v) {
            $result[$k] = $this->$k;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Saves the file data
     * @return mixed
     */
    abstract public function saveData();

    public function setID($id)
    {
        $this->ID = $id;
        return $this;
    }


}