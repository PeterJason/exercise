<?php
namespace app\controllers;

use app\libraries\Controller;
use app\models\PhoneNumber;
use app\libraries\Paginator;

class Pages extends Controller {
    /**
     * @var array|null
     */
    private $_countriesList;
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
        $allRecords = $this->_numbers->find('customer', 'phone')->search();
        $this->_numbers->setNumbers($allRecords);
        $this->_countriesList = $this->_numbers->parseNumbers()->allDistinctCountries();
        //set a global settings pagination
        $this->_page = $this->pageNumber();
        $this->_numbers->setLimit(self::LIMIT_PER_PAGE);
        $this->_numbers->setPage($this->_page); //used to calculate offset
    }

    /**
     * Display all numbers available in database
     */
    public function index() : void {
        $numbersList = $this->_numbers->limitListNumbers();

        $paginator = new Paginator(URL_ROOT . 'index', "Prev", "Next");
        $paginator->pagination($this->_page, $this->_numbers->countListNumbers(), self::LIMIT_PER_PAGE);

        $data = [
            'numbers' => $numbersList,
            'countries' => $this->_countriesList,
            'pagination' => $paginator->render()
        ];

        $this->view('pages/index', $data);
    }

    /**
     * Display all numbers of one specific state (OK or NOK)
     * @param $state
     */
    public function stateNumber($state) : void {
        $numbersList = $this->_numbers->checkStateNumbers($state)->limitListNumbers();

        $paginator = new Paginator(URL_ROOT . 'pages/stateNumber/'.$state, "Prev", "Next");
        $paginator->pagination($this->_page, $this->_numbers->countListNumbers(), self::LIMIT_PER_PAGE);
        
        $data = [
            'numbers' => $numbersList,
            'countries' => $this->_countriesList,
            'pagination' => $paginator->render()
        ];

        $this->view('pages/index', $data);
    }

    /**
     * Display all numbers of one specific country
     * @param $country
     */
    public function countryNumber($country) : void {
        $numbersList = $this->_numbers->checkCountryNumbers($country)->limitListNumbers();
        
        $paginator = new Paginator(URL_ROOT . 'pages/countryNumber/'.$country, "Prev", "Next");
        $paginator->pagination($this->_page, $this->_numbers->countListNumbers(), self::LIMIT_PER_PAGE);

        $data = [
            'numbers' => $numbersList,
            'countries' => $this->_countriesList,
            'pagination' => $paginator->render()
        ];

        $this->view('pages/index', $data);
    }
}