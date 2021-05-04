<?php
namespace app\controllers;

use app\libraries\Controller;
use app\models\PhoneNumber;
use app\libraries\Paginator;

class Pages extends Controller {
    /**
     * @var array|null
     */
    private $_countries_list;
    /**
     * @var PhoneNumber
     */
    private $_numbers;
    /**
     * @var int
     */
    private $_page;
    /**
     * Limit the number of rows per page
     * @const int
     */
    const LIMIT_PER_PAGE = 5;

    /**
     * Pages constructor.
     */
    public function __construct() {
        $this->_numbers = new PhoneNumber();
        $all_records = $this->_numbers->find('customer', 'phone')->search();
        $this->_numbers->setNumbers($all_records);
        $this->_countries_list = $this->_numbers->parseNumbers()->all_distinct_countries();
        //set settings pagination
        $this->_page = $this->pageNumber();
        $this->_numbers->setLimit(self::LIMIT_PER_PAGE);
        $this->_numbers->setPage($this->_page); //used to calculate offset
    }

    /**
     * Display all numbers available in database
     */
    public function index() : void {
        $numbers_list = $this->_numbers->limitListNumbers();

        $paginator = new Paginator(URL_ROOT . 'index/page/', "Prev", "Next");
        $paginator->pagination($this->_page, $this->_numbers->countListNumbers(), self::LIMIT_PER_PAGE);
        
        $data = [
            'numbers' => $numbers_list,
            'countries' => $this->_countries_list,
            'pagination' => $paginator->render()
        ];

        $this->view('pages/index', $data);
    }

    /**
     * Display all numbers of one specific state (OK or NOK)
     * @param $state
     */
    public function stateNumber($state) : void {
        $numbers_list = $this->_numbers->checkStateNumbers($state)->limitListNumbers();

        $paginator = new Paginator(URL_ROOT . 'pages/stateNumber/'.$state.'/page/', "Prev", "Next");
        $paginator->pagination($this->_page, $this->_numbers->countListNumbers(), self::LIMIT_PER_PAGE);
        
        $data = [
            'numbers' => $numbers_list,
            'countries' => $this->_countries_list,
            'pagination' => $paginator->render()
        ];

        $this->view('pages/index', $data);
    }

    /**
     * Display all numbers of one specific country
     * @param $country
     */
    public function countryNumber($country) : void {
        $numbers_list = $this->_numbers->checkCountryNumbers($country)->limitListNumbers();
        
        $paginator = new Paginator(URL_ROOT . 'pages/countryNumber/'.$country.'/page/', "Prev", "Next");
        $paginator->pagination($this->_page, $this->_numbers->countListNumbers(), self::LIMIT_PER_PAGE);

        $data = [
            'numbers' => $numbers_list,
            'countries' => $this->_countries_list,
            'pagination' => $paginator->render()
        ];

        $this->view('pages/index', $data);
    }
}