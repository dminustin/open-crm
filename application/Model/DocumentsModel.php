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

    /**
     * Adds a new note (document with an empty attachment)
     * @param $data
     * @throws \OpenCRM\Exception\InvalidArgumentException
     */
    public static function writeNewNote($data)
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
        print_r($sqls);
        $sql = "INSERT INTO documents SET created_at=NOW(), file_id=NULL, " . join(", ", $sqls);
        db()->exec($sql);
        Application::app()->addDisplayMessage('info', "Note added successfully");
    }

    /**
     * @param $data array $_POST
     * @param $files array $_FILES
     * @throws CommonException
     * @throws \OpenCRM\Exception\InvalidArgumentException
     */
    public static function uploadNewDocuments($data, $files)
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


    public static function getDocumentsList($terms = [])
    {
        //todo apply terms
        $res = db()->query("SELECT 
        documents.id, 
        contacts.display_name,
        users.name,
        documents.io_type,
        documents.title,
        documents.description,
        documents.created_at,
        
        files_data.id as files_data_id,
        files_data.mime_type as files_data_mime_type,
        files_data.file_type as files_data_file_type,
        files_data.file_size as files_data_file_size,
        files_data.file_name as files_data_file_name,
        files_data.created_at as files_data_created_at,
        
        documents.added_by
        
        FROM documents
        LEFT JOIN files_data
        ON files_data.id=documents.file_id
        
        LEFT JOIN contacts
        ON contacts.id = documents.contact_id
        
        LEFT JOIN users
        ON users.id = documents.added_by");
        if (!$res->rowCount()) {
            return [];
        }
        $result = $res->fetchAll();

        array_walk($result, function(&$a) {
            $fd = [
                'id'=>$a['files_data_id'],
                'mime_type'=>$a['files_data_mime_type'],
                'file_type'=>$a['files_data_file_type'],
                'file_size'=>$a['files_data_file_size'],
                'file_name'=>$a['files_data_file_name'],
                'created_at'=>$a['files_data_created_at']
            ];
            unset(
                $a['files_data_id'],
                $a['files_data_mime_type'],
                $a['files_data_file_type'],
                $a['files_data_file_size'],
                $a['files_data_file_name'],
                $a['files_data_created_at']
            );

            $a['file_url'] = (new FileStorageFS())->getFileUrl(FileDataFS::createByArray($fd));
            $a['file_type'] = $fd['file_type'];
        });

        return $result;
    }



}