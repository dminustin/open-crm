<?php
/** For more info about namespaces plase @see http://php.net/manual/en/language.namespaces.importing.php */

namespace OpenCRM\Core;

use OpenCRM\Controller\Ajax\AjaxLogin;
use OpenCRM\Controller\Ajax\AjaxSysMessages;
use OpenCRM\Controller\Ajax\Contacts\AjaxAdd;
use OpenCRM\Controller\App\Contacts\ContactsAdd;
use OpenCRM\Controller\App\Contacts\ContactsList;
use OpenCRM\Controller\App\Dashboard\DashboardView;
use OpenCRM\Controller\App\Documents\DocumentsAdd;
use OpenCRM\Controller\App\Documents\DocumentsList;
use OpenCRM\Controller\App\Documents\DocumentsPost;
use OpenCRM\Controller\App\Documents\DocumentsPostNote;
use OpenCRM\Controller\App\Documents\DocumentsWriteNote;
use OpenCRM\Controller\App\File\FileGet;
use OpenCRM\Controller\HomeController;
use Symfony\Component\Cache\Adapter\AbstractAdapter;


class Application
{
    /**
     * @var $app Application
     */
    protected static $app;

    /** @var array Config parameters */
    public $config = [];
    /**
     * @var \PDO
     */
    public $db;
    /**
     * @var AbstractAdapter
     */
    public $cache;
    /**
     * @var \Twig\Environment
     */
    protected $render;

    /**
     * "Construct" the application:
     */
    private function __construct()
    {

        //Load config file
        $config_file = (file_exists(APP . 'Config/config.local.json')) ? APP . 'Config/config.local.json' : APP . 'Config/config.json';
        $this->config = json_decode(file_get_contents($config_file), true);

        //Initialize the Twig
        $loader = new \Twig\Loader\FilesystemLoader(APP . 'View/');
        $this->render = new \Twig\Environment($loader, [
            'cache' => $this->config['TWIG_CACHE'],
        ]);


        //Init DB
        $init = sprintf('%s:host=%s;dbname=%s;charset=%s',
            $this->config['DB_TYPE'],
            $this->config['DB_HOST'],
            $this->config['DB_NAME'],
            $this->config['DB_CHARSET']

        );
        $this->db = new \PDO($init, $this->config['DB_USER'], $this->config['DB_PASS']);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        //Init cache
        $this->cache = Cache::getInstance($this->config['CACHE_TYPE']);

        set_exception_handler(function ($ex) {
            static::handleException($ex);
        });


    }

    /**
     * @param $ex \Throwable
     */
    protected static function handleException($ex)
    {
        echo $ex->getMessage();
        die();
    }

    /**
     * Use this method to get an Application class
     * @return Application
     */
    public static function app()
    {
        if (empty(static::$app)) {
            static::$app = new Self();
        }
        return static::$app;
    }

    public function addDisplayMessage($type, $message)
    {
        if (!isset($_SESSION['messages']) || !is_array($_SESSION['messages'])) {
            $_SESSION['messages'] = [];
        }
        $_SESSION['messages'][] = [$type, $message];
    }


    /**
     * Render the template
     * @param $template
     * @param array $vars
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($template, $vars = [])
    {
        $vars['userLogged'] = userLogged();
        echo $this->render->render($template, $vars);
    }


    /**
     * Redirects to given URL
     * @param $url
     */
    public function redirect($url)
    {
        header("Location: {$url}", true);
        exit(0);
    }


    /**
     * Run the Web App
     */
    public function run()
    {
        $router = new \Bramus\Router\Router();

        $router->all('/', function () {
            HomeController::indexPage();
        });

        $router->post('/ajax/login', function () {
            AjaxLogin::run();
        }
        );


        if (userLogged()) {

            //====================================================
            // Common requests
            $router->all('/app/dashboard', function () {
                DashboardView::run();
            });
            $router->all('/app/contacts/add', function () {
                ContactsAdd::run();
            });
            $router->all('/app/contacts/list', function () {
                ContactsList::run();
            });

            $router->all('/app/documents/list', function () {
                DocumentsList::run();
            });
            $router->all('/app/documents/add', function () {
                DocumentsAdd::run();
            });
            $router->post('/app/documents/post', function () {
                DocumentsPost::run();
            });
            $router->post('/app/documents/postnote', function () {
                DocumentsPostNote::run();
            });
            $router->all('/app/documents/write', function () {
                DocumentsWriteNote::run();
            });

            $router->all('/app/file/get', function () {
                FileGet::run();
            });


            //=====================================================
            // AJAX REQUESTS
            $router->all('/ajax/contacts/add', function () {
                AjaxAdd::run();
            });
            $router->all('/ajax/contacts/list', function () {
                \OpenCRM\Controller\Ajax\Contacts\AjaxList::run();
            });
            $router->all('/ajax/sysmessages', function () {
                AjaxSysMessages::run();
            });

            $router->all('/ajax/documents/list', function () {
                \OpenCRM\Controller\Ajax\Documents\AjaxList::run();
            });


        }

        $router->set404(function () {
            header('HTTP/1.1 404 Not Found');
            HomeController::e404();
        });

        $router->run();

    }


}
