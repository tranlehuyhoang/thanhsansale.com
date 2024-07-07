<?php

namespace App\Services\Common;

class Pagination
{
    protected $baseURL = '';
    protected $totalRows = '';
    protected $perPage = 10;
    protected $numLinks = 2;
    protected $currentPage = 0;
    protected $firstLink = 'First';
    protected $nextLink = '>';
    protected $prevLink = '<';

    protected $lastLink = 'Last';
    protected $fullTagOpen = '<ul class="pagination justify-content-end font-weight-semi-bold mb-0">';
    protected $fullTagClose = '</ul>';
    protected $firstTagOpen = '';
    protected $firstTagClose = '&nbsp;';
    protected $lastTagOpen = '&nbsp;';
    protected $lastTagClose = '';
    protected $curTagOpen = '<li class="page-item d-none d-md-block">';
    protected $curTagClose = '</li>';
    protected $nextTagOpen = '<li class="page-item">';
    protected $nextTagClose = '</li>';
    protected $prevTagOpen = '<li class="page-item">';
    protected $prevTagClose = '</li>';
    protected $numTagOpen = '<li class="page-item d-none d-md-block">';
    protected $numTagClose = '</li>';
    protected $showCount = true;
    protected $currentOffset = 0;
    protected $queryStringSegment = 'page';

    function __construct($params = array())
    {
        if (count($params) > 0) {
            $this->initialize($params);
        }
    }

    function initialize($params = array())
    {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }
    }

    function createdShowing()
    {
        // Nếu tổng số dòng là không, không cần tiếp tục
        if ($this->totalRows == 0 or $this->perPage == 0) {
            return '';
        }
        // Tính toán tổng số trang
        $numPages = ceil($this->totalRows / $this->perPage);
        // Chỉ có một trang? không cần tiếp tục
        if ($numPages == 1) {
            if ($this->showCount) {
                $info = '<div class="d-flex mb-2 mb-md-0">Đang hiển thị ' . $this->totalRows . '</div>';
                return $info;
            } else {
                return '';
            }
        }
        // Xác định chuỗi truy vấn
        $query_string_sep = '/';
        $this->baseURL = $this->baseURL . $query_string_sep;

        $URL = $_SERVER['REQUEST_URI']; // /book/page/1
        // get the last segment from the URL, if there is no page number then return 0
        $this->currentPage = substr($URL, strrpos($URL, '/') + 1);

        if (!is_numeric($this->currentPage) || $this->currentPage == 0) {
            $this->currentPage = 1;
        }

        // Chuỗi nội dung liên kết biến
        $output = '';

        // Thông báo hiển thị liên kết
        if ($this->showCount) {
            $currentOffset = ($this->currentPage > 1) ? ($this->currentPage - 1) * $this->perPage : $this->currentPage;
            $info = '<div class="d-flex mb-2 mb-md-0">Đang hiển thị ' . $currentOffset . ' đến ';

            if (($currentOffset + $this->perPage) <= $this->totalRows)
                $info .= $this->currentPage * $this->perPage;
            else
                $info .= $this->totalRows;

            $info .= ' trong tổng số ' . $this->totalRows . ' mục </div>';

            $output .= $info;
        }

        return $output;
    }
    /**
     * Generate the pagination links
     */
    function createLinks()
    {
        // If total number of rows is zero, do not need to continue
        if ($this->totalRows == 0 or $this->perPage == 0) {
            return '';
        }
        // Calculate the total number of pages
        $numPages = ceil($this->totalRows / $this->perPage);

        $query_string_sep = '/';
        $this->baseURL = $this->baseURL . $query_string_sep;

        $URL = $_SERVER['REQUEST_URI']; // /book/page/1
        // get the last segment from the URL, if there is no page number then return 0
        $this->currentPage = substr($URL, strrpos($URL, '/') + 1);

        if (!is_numeric($this->currentPage) || $this->currentPage == 0) {
            $this->currentPage = 1;
        }
        $output = '';
        $this->numLinks = (int) $this->numLinks;

        // Is the page number beyond the result range? the last page will show
        if ($this->currentPage > $this->totalRows) {
            $this->currentPage = $numPages;
        }

        $uriPageNum = $this->currentPage;

        // Calculate the start and end numbers. 
        $start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
        $end = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;

        // Render the "First" link
        if ($this->currentPage > $this->numLinks) {
            $firstPageURL = $this->baseURL . '/1';
            $output .= $this->firstTagOpen . '<a  class="page-link active"  href="' . $firstPageURL . '">' . $this->firstLink . '</a>' . $this->firstTagClose;
        }
        // Render the "previous" link
        if ($this->currentPage != 1) {
            $i = ($uriPageNum - 1);
            if ($i == 0)
                $i = '';
            $output .= $this->prevTagOpen . '<a id="datatablePaginationPrev" class="page-link"  aria-label="Previous" href="' . $this->baseURL . $i . '">' . $this->prevLink . '</a>' . $this->prevTagClose;
        }
        // Write the digit links
        for ($loop = $start; $loop <= $end; $loop++) {

            $i = $loop;

            if ($this->currentPage == $loop) {
                $output .= $this->curTagOpen . '<a id="datatablePaginationPage' . $loop . '" class="page-link active" href="' . $this->baseURL . $i . '" data-dt-page-to="0">' . $loop . '</a>' . $this->curTagClose;
            } else {
                $output .= $this->numTagOpen . '<a id="datatablePagination' . $i . '" class="page-link" data-dt-page-to="' . $i . '" href="' . $this->baseURL . $i . '">' . $loop . '</a>' . $this->numTagClose;
            }
        }
        // Render the "next" link
        if ($this->currentPage < $numPages) {
            $i = ($this->currentPage + 1);
            $output .= $this->nextTagOpen . '<a class="page-link" aria-label="Next" href="' . $this->baseURL . $i . '">' . $this->nextLink . '</a>' . $this->nextTagClose;
        }
        // Render the "Last" &p
        if (($this->currentPage + $this->numLinks) < $numPages) {
            $i = $numPages;
            $output .= $this->lastTagOpen . '<a id="datatablePaginationPage' . $i . '" class="page-link active" data-dt-page-to="' . $i . '" href="' . $this->baseURL . $i . '">' . $this->lastLink . '</a>' . $this->lastTagClose;
        }
        // Remove double slashes
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->fullTagOpen . $output . $this->fullTagClose;

        $this->baseURL = $this->baseURL . '/' . $this->queryStringSegment;

        return $output;
    }
}
