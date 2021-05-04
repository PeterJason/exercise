<?php
namespace app\libraries;

/*
 * App Core Class
 * Create URL & loads core controller
 * URL FORMAT -/controller/method/params
 */

class Core {
    /**
     * @var mixed|string
     */
    protected $currentController = "Pages";
    /**
     * @var string
     */
    protected $currentMethod = "index";
    /**
     * @var array|false|string[]
     */
    protected $params = [];

    /**
     * Core constructor.
     */
    public function __construct(){
        $url = $this->getUrl();

        //Look in controllers for first value
        if(!empty($url[0]) && file_exists(dirname(__FILE__, 2) . '/controllers/' . ucwords($url[0]) . '.php')){
            //if exists, set as controller
            $this->currentController = ucwords($url[0]);
            //Unset 0 index
            unset($url[0]);
        }

        //instantiate controller class
        $class = 'app\\controllers\\' . $this->currentController;
        
        $this->currentController = new $class;

        //check for second part of url
        if(isset($url[1])){
            //Check to see if method exists in controller
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                //Unset 1 index
                unset($url[1]);
            }
        }
        
        //Get params
        $this->params = $url ? array_values($url) : [];

        //Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    /**
     * @return false|string[]
     */
    public function getUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        } else {
            $_GET['url'] = $this->currentMethod; 
        }
    }
}