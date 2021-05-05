<?php
namespace app\models;
use app\libraries\DataSearch;

class PhoneNumber extends DataSearch
{
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
    private $_numberList = array();

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
    public function __construct(){ }

    /**
     * @return PhoneNumber|null
     */
    public function parseNumbers() : ?PhoneNumber
    {
        foreach ($this->_numbers as $number) {
            $this->_number = $number['phone'];
            $regNumberArray = preg_match('/\((\d+)\)\s(.+)/', $this->_number, $output);
            if ($regNumberArray) {
                $this->_indicative = $output[1] ?? null;
                $smallNumber = $output[2] ?? null;
                $isValid = $this->isValid();
                $countryName = $this->checkCountry();
            }
            $this->_numberList[] = array('country' => $countryName, 'state' => $isValid, 'countryCode' => $this->_indicative, 'phoneNumber' => $smallNumber);
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function isValid() : bool
    {
        $regexValidate = self::$_validator[$this->_indicative]['regex'];
        return preg_match("/$regexValidate/", $this->_number, $output);
    }

    /**
     * @return string
     */
    private function checkCountry() : string
    {
        return self::$_validator[$this->_indicative]['country'];
    }

    /**
     * @param $state
     * @return PhoneNumber
     */
    public function checkStateNumbers($state) : PhoneNumber
    {
        $state = $state === 'valid';
        $numbers_list = $this->_numberList;
        $this->_numberList = array();
        
        foreach ($numbers_list as $numbers) {
            if($numbers['state'] === $state) {
                $this->_numberList[] = $numbers;
            }
        }

        return $this;
    }

    /**
     * return all distinct countries found on all numbers
     * @return array|null
     */
    public function allDistinctCountries() : ?array
    {
        $countries = array();

        foreach ($this->_numberList as $number) {
            if (!in_array($number['country'], $countries, true)) {
                array_push($countries, $number['country']);
            }
        }
        return $countries;
    }

    /**
     * @param $country
     * @return PhoneNumber|null
     */
    public function checkCountryNumbers($country) : ?PhoneNumber
    {
        $numbersList = $this->_numberList;
        $this->_numberList = array();

        foreach ($numbersList as $number) {
            if($number['country'] === $country) {
                $this->_numberList[] = $number;
            }
        }

        return $this;
    }

    /**
     * return the numbers by limit defined (includes offset and limit of rows)
     * @return array|null
     */
    public function limitListNumbers() : ?array
    {
        $numbersList = array();
        $offset = (($this->_page * $this->_limit) - $this->_limit >= 0 ? ($this->_page * $this->_limit) - $this->_limit : 0);

        for($i=$offset; $i < $offset+$this->_limit; $i++) {
            if (isset($this->_numberList[$i])) {
                $numbersList[] = $this->_numberList[$i];
            }
        }

        return $numbersList;
    }

    /**
     * count total numbers
     * @return int
     */
    public function countListNumbers() : int
    {
        return count($this->_numberList);
    }

    /**
     * Set all numbers
     * @param $numbers
     */
    public function setNumbers($numbers) : void
    {
        $this->_numbers = $numbers;
    }

    /**
     * Set limit
     * @param $limit
     */
    public function setLimit($limit) : void
    {
        $this->_limit = $limit;
    }

    /**
     * set page
     * @param $page
     */
    public function setPage($page) : void
    {
        $this->_page = $page;
    }
}