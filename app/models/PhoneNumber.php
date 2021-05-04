<?php
namespace app\models;
use app\libraries\DataSearch;

class PhoneNumber extends DataSearch {
    /**
     * @var
     */
    private $_number;
    /**
     * @var
     */
    private $_numbers;
    /**
     * @var
     */
    private $_indicative;
    /**
     * @var
     */
    private $_limit;
    /**
     * @var
     */
    private $_page;
    /**
     * @var array
     */
    private $_number_list = array();

    /**
     * @var string[][]
     */
    private static $_validator = array (
        237 => array('country' => 'Cameroon', 'regex' => '\(237\)\ ?[2368]\d{7,8}$'),
        251 => array('country' => 'Ethiopia', 'regex' => '\(251\)\ ?[1-59]\d{8}$'),
        212 => array('country' => 'Morocco', 'regex' => '\(212\)\ ?[5-9]\d{8}$'),
        258 => array('country' => 'Mozambique', 'regex' => '\(258\)\ ?[28]\d{7,8}$'),
        256 => array('country' => 'Uganda', 'regex' => '\(256\)\ ?\d{9}$')
    );

    /**
     * PhoneNumber constructor.
     */
    public function __construct(){    }

    /**
     * @return DataSearch|null
     */
    public function parseNumbers() : ?DataSearch {
        foreach ($this->_numbers as $number) {
            $this->_number = $number['phone'];
            $reg_number_array = preg_match('/\((\d+)\)\s(.+)/', $this->_number, $output);
            if ($reg_number_array) {
                $this->_indicative = $output[1] ?? null;
                $small_number = $output[2] ?? null;
                $is_valid = $this->is_Valid();
                $country_name = $this->check_country();
            }
            $this->_number_list[] = array('country' => $country_name, 'state' => $is_valid, 'countryCode' => $this->_indicative, 'phoneNumber' => $small_number);
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function is_Valid() : bool {
        $regex_validate = self::$_validator[$this->_indicative]['regex'];
        $number_validation = preg_match("/$regex_validate/", $this->_number, $output);
        return $number_validation;
    }

    /**
     * @return string
     */
    private function check_country() : string {
        return self::$_validator[$this->_indicative]['country'];;
    }

    /**
     * @param $state
     * @return DataSearch
     */
    public function checkStateNumbers($state) : DataSearch {
        $state = $state == 'valid' ? true : false;
        $numbers_list = $this->_number_list;
        $this->_number_list = array();
        
        foreach ($numbers_list as $numbers) {
            if($numbers['state'] == $state) {
                $this->_number_list[] = $numbers;
            }
        }

        return $this;
    }

    /**
     * return all distinct countries found on all numbers
     * @return array|null
     */
    public function all_distinct_countries() : ?array {
        $countries = array();
        $insert = true;

        foreach ($this->_number_list as $number) {
            foreach ($countries as $country) {
                if($number['country']===$country) {
                    $insert = false;
                }
            }
            if ($insert === true) {
                array_push($countries, $number['country']);
            }
            $insert = true;
        }

        return $countries;
    }

    /**
     * @param $country
     * @return DataSearch|null
     */
    public function checkCountryNumbers($country) : ?DataSearch {
        $numbers_list = $this->_number_list;
        $this->_number_list = array();

        foreach ($numbers_list as $number) {
            if($number['country'] == $country) {
                $this->_number_list[] = $number;
            }
        }

        return $this;
    }

    /**
     * return the numbers by limit defined (includes offset and limit of rows)
     * @return array|null
     */
    public function limitListNumbers() : ?array {
        $numbers_list = array();
            $offset = (($this->_page * $this->_limit) - $this->_limit >= 0 ? ($this->_page * $this->_limit) - $this->_limit : 0);

        for($i=$offset; $i < $offset+$this->_limit; $i++) {
                if (isset($this->_number_list[$i])) {
                    $numbers_list[] = $this->_number_list[$i];
                }
        }

        return $numbers_list;
    }

    /**
     * count total numbers
     * @return int
     */
    public function countListNumbers() : int {
        return count($this->_number_list);
    }

    /**
     * Set all numbers
     * @param $numbers
     */
    public function setNumbers($numbers) : void {
        $this->_numbers = $numbers;
    }

    /**
     * Set limit
     * @param $limit
     */
    public function setLimit($limit) : void {
        $this->_limit = $limit;
    }

    /**
     * set page
     * @param $page
     */
    public function setPage($page) : void {
        $this->_page = $page;
    }
}