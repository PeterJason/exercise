<?php
namespace app\libraries;

/*
 * Base Controller
 * Loads the models and views
 */
class Controller {

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
        $page_number = 1;
        foreach ($urlArray as $key => $url) {
            if($url == 'page') {
                $page_number = is_numeric($urlArray[$key+1]) ? $urlArray[$key+1] : 1;
                break;
            }
        }
        return $page_number;
    }
}