<?php
namespace OpenCRM\Console;


use OpenCRM\Core\Console;

class Migration extends Console
{
    /**
     * Run the migrations
     */
    public static function run()
    {
        $migrations = glob(ROOT . 'migrations/Migration_*.php');
        foreach ($migrations as $filename) {
            preg_match('#Migration_([a-z0-9A-Z]+)\\.php$#', $filename, $preg);
            if (!isset($preg[1])) {
                static::error('File name must be Migration_[AAAAAAAA].php , where AAAAAAA is alphabetical or numbers');
            }
            $id = $preg[1];
            if (static::isMigrationExists($id)) {
                static::log("${id} exists");
                continue;
            }


            static::log("Start execute ${id}");
            require_once($filename);
            $class = 'Migration_' . $id;
            $result = call_user_func("${class}::run");

            if ($result) {
                db()->exec('insert into migration set id="'.$id.'"');
            } else {
                static::log("Error with executing ${id}");
                return;
            }
            static::log("Done execute ${id}");

        }
    }

    /**
     * Check if migration is already installed
     * @param $id
     *
     * @return boolean
     */
    protected static function isMigrationExists($id) {
        return (bool) (db()->query('select count(*) as cnt from migration where id="'.$id.'"')->fetch()['cnt']);
    }
}