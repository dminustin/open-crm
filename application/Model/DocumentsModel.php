<?php
/**
 * Created by PhpStorm.
 * User: danila
 * Date: 20.04.19
 * Time: 23:36
 */

namespace OpenCRM\Model;


use OpenCRM\Core\Application;
use OpenCRM\Core\Model;
use OpenCRM\Core\PrepareInputValues;
use OpenCRM\Exception\CommonException;
use OpenCRM\Model\FileStorageFS\FileDataFS;
use OpenCRM\Model\FileStorageFS\FileStorageFS;

class DocumentsModel extends Model
{

    const DTYPE_INBOX = 'inbox';
    const DTYPE_OUTBOX = 'outbox';
    const DTYPE_NOTES = 'notes';
    const DTYPE_INTERNAL_INBOX = 'int_inbox';
    const DTYPE_INTERNAL_OUTBOX = 'int_outbox';
    const DTYPE_OTHER = 'other';

    public static $DTYPES = [
        self::DTYPE_INBOX => 'Incoming Data from contact',
        self::DTYPE_OUTBOX => 'Outgoing data to contact',
        self::DTYPE_NOTES => 'Notes',
        self::DTYPE_INTERNAL_INBOX => 'Incoming Data from CRM user',
        self::DTYPE_INTERNAL_OUTBOX => 'Outgoing data to CRM user',
        self::DTYPE_OTHER => 'Unknown i/o type',
    ];

    static $prepare_fields = [
        'title' => PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'description' => PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'io_type' => PrepareInputValues::PIV_AL_NUM_SYMBOLS,
        'contact_id' => PrepareInputValues::PIV_NUM,
        'added_by' => PrepareInputValues::PIV_NUM,
    ];

    /**
     * We have to check this fields
     * @var array
     */
    static $required_fileds = [
        'title',
        'io_type',
        'contact_id',
    ];


    static function uploadNewDocuments($data, $files)
    {
        $sqls = [];
        foreach (static::$prepare_fields as $field => $type) {
            $val = isset($data[$field]) ? $data[$field] : null;
            if (empty($val) && !in_array($field, static::$required_fileds)) {
                $sqls[$field] = "";
                continue;
            }
            $val = PrepareInputValues::escapeTheInput($val, $type);
            if (empty($val) && in_array($field, static::$required_fileds)) {
                throw new InvalidArgumentException("Empty value for ${field} not allowed");
            }
            $sqls[] = "$field = '$val'";
        }

        $cnt = count($files['file']['name']);
        $uploaded = [];
        for ($i = 0; $i < $cnt; $i++) {
            if ($files['file']['error'][$i]) {
                //todo throw exception
                continue;
            }

            db()->beginTransaction();
            $file_data = FileDataFS::fillByRealFile($files['file']['tmp_name'][$i], $files['file']['name'][$i]);
            $file_data->saveData();
            if (!(new FileStorageFS())->uploadFile($files['file']['tmp_name'][$i], $file_data)) {
                db()->rollBack();
                continue;
            } else {
                db()->commit();
                $uploaded[] = $file_data;
            }
        }


        if (empty($uploaded)) {
            throw new CommonException("Empty files list");
        }

        foreach ($uploaded as $file) {
            $sql = "INSERT INTO documents SET created_at=NOW(), file_id={$file->ID}, " . join(", ", $sqls);
            db()->exec($sql);
            Application::app()->addDisplayMessage('info', "File {$file->originalName} uploaded successfully");
        }




    }


}