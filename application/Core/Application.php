<?php
/** For more info about namespaces plase @see http://php.net/manual/en/language.namespaces.importing.php */
namespace OpenCRM\Core;

class Application
{
    /**
     * @var $app Application
     */
    private static $app;

    /** @var array Config parameters */
    public $config = [];

    /** @var null The controller */
    private $url_controller = null;

    /** @var null The method (of the above controller), often also named "action" */
    private $url_action = null;

    /** @var array URL parameters */
    private $url_params = [];

    /**
     * @var \Twig\Environment
     */
    private $render;

    /**
     * @var \PDO
     */
    public $db;


    /**
     * Use this method to get an Application class
     * @return Application
     */
    static function app() {
        if (empty(static::$app)) {
            static::$app = new Self();
        }
        return static::$app;
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
    public function render($template, $vars=[]) {
        return $this->render->render($template, $vars);
    }


    /**
     * Run the Web App
     */
    public function run() {
        // create array with URL parts in $url
        $this->splitUrl();

        // check for controller: no controller given ? then load start-page
        if (!$this->url_controller) {

            $page = new \OpenCRM\Controller\HomeController();
            $page->index();

        } elseif (file_exists(APP . 'Controller/' . ucfirst($this->url_controller) . 'Controller.php')) {
            // here we did check for controller: does such a controller exist ?

            // if so, then load this file and create this controller
            // like \OpenCRM\Controller\CarController
            $controller = "\\OpenCRM\\Controller\\" . ucfirst($this->url_controller) . 'Controller';
            $this->url_controller = new $controller();

            // check for method: does such a method exist in the controller ?
            if (method_exists($this->url_controller, $this->url_action) &&
                is_callable(array($this->url_controller, $this->url_action))) {

                if (!empty($this->url_params)) {
                    // Call the method and pass arguments to it
                    call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);
                } else {
                    // If no parameters are given, just call the method without parameters, like $this->home->method();
                    $this->url_controller->{$this->url_action}();
                }

            } else {
                if (strlen($this->url_action) == 0) {
                    // no action defined: call the default index() method of a selected controller
                    $this->url_controller->index();
                } else {
                    $page = new \OpenCRM\Controller\ErrorController();
                    $page->index();
                }
            }
        } else {
            $page = new \OpenCRM\Controller\ErrorController();
            $page->index();
        }

    }

    /**
     * "Construct" the application:
     */
    private function __construct()
    {
        //Initialize the Twig
        $loader = new \Twig\Loader\FilesystemLoader(APP . 'View/');
        $this->render = new \Twig\Environment($loader, [
            //'cache' => '/path/to/compilation_cache',
        ]);


        //Load config file
        $config_file = (file_exists(APP . 'Config/config.local.json')) ? APP . 'Config/config.local.json' : APP . 'Config/config.json';
        $this->config = json_decode(file_get_contents($config_file), true);


        //Init DB
        $init = sprintf('%s:host=%s;dbname=%s',
            $this->config['DB_TYPE'],
            $this->config['DB_HOST'],
            $this->config['DB_NAME']);
        $this->db = new \PDO($init, $this->config['DB_USER'], $this->config['DB_PASS']);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // split URL
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Put URL parts into according properties
            // By the way, the syntax here is just a short form of if/else, called "Ternary Operators"
            // @see http://davidwalsh.name/php-shorthand-if-else-ternary-operators
            $this->url_controller = isset($url[0]) ? $url[0] : null;
            $this->url_action = isset($url[1]) ? $url[1] : null;

            // Remove controller and action from the split URL
            unset($url[0], $url[1]);

            // Rebase array keys and store the URL params
            $this->url_params = array_values($url);

            // for debugging. uncomment this if you have problems with the URL
            //echo 'Controller: ' . $this->url_controller . '<br>';
            //echo 'Action: ' . $this->url_action . '<br>';
            //echo 'Parameters: ' . print_r($this->url_params, true) . '<br>';
        }
    }
}
