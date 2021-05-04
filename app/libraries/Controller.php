<?php
namespace app\libraries;

/*
 * Base Controller
 * Loads the models and views
 */
class Controller {
    /**
     * Load model
     * @param $model
     * @return mixed
     */
    /*public function model($model) {
        //Require model file
        require_once '../app/models/' . $model . ".php";
        
        //instantiate model
        $class = 'app\\models\\' . $model;
        return new $class();
    }*/

    /**
     * Load view
     * @param $view
     * @param array $data
     */
    public function view($view, $data = []) {
        $data = (object)$data;
        //Check for view file
        if (file_exists('../app/views/' . $view . '.php')){
            require_once '../app/views/' . $view . '.php';
        } else {
            //View does not exists
            die("View does not exists");
        }
    }

    /**
     * Get page number from URL
     * @return int
     */
    public function pageNumber() : int {
        $urlArray = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_STRING));
        $page_number = 0;

        foreach ($urlArray as $key => $url) {
            if($url == 'page') {
                $page_number = is_numeric($urlArray[$key+1]) ? $urlArray[$key+1] : 1;
                break;
            } else {
                $page_number = 1;
            }
        }
        return $page_number;
    }
}