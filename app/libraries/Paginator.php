<?php
namespace app\libraries;

class Paginator
{
    /**
     * @var
     */
    private $_totalRecords;
    /**
     * @var
     */
    private $_limit;
    /**
     * @var
     */
    private $_url;
    /**
     * @var
     */
    private $_firstPageName;
    /**
     * @var
     */
    private $_lastPageName;
    /**
     * @var
     */
    private $_firstPageNumber;
    /**
     * @var
     */
    private $_lastPageNumber;

    /**
     * Paginator constructor.
     * Initialize the default data to create pagination
     * @param $url
     * @param $firstPageName
     * @param $lastPageName
     */
    public function __construct($url, $firstPageName, $lastPageName)
    {
        $this->_url = $url;
        $this->_firstPageName = $firstPageName;
        $this->_lastPageName = $lastPageName;
    }

    /**
     * @param $pageNumber
     * @param $totalRecords
     * @param $limit
     */
    public function pagination($pageNumber, $totalRecords, $limit) : void
    {
        $this->_totalRecords = $totalRecords;
        $this->_limit = $limit;
        $this->_firstPageNumber = $pageNumber <= 1 ? 1 : $pageNumber - 1;
        $this->_lastPageNumber = (($this->_limit * $pageNumber) < $this->_totalRecords ) ? $pageNumber + 1 : $pageNumber;
    }

    /**
     * Generate HTML page
     * @return string
     */
    public function render() : string
    {
        $html  = '<nav aria-label="Page navigation example">';
        $html .= '<ul class="pagination float-right">';
        $html .= "<li class='page-item'><a class='page-link' href='{$this->_url}/page/{$this->_firstPageNumber}'>{$this->_firstPageName}</a></li>";
        $html .= "<li class='page-item'><a class='page-link' href='{$this->_url}/page/{$this->_lastPageNumber}'>{$this->_lastPageName}</a></li>";
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $this->_totalRecords > $this->_limit ? $html : "";
    }
}
