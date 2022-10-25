<?php


namespace App\Model\Api;

use App\Model\Api;
use App\Model\View;

class Google extends Api
{
    const ENDPOINT = "https://www.googleapis.com/books/v1/volumes";
    const RESULTS_TYPE = "book";
    const ATTRIBUTES = array(
        'doc_id' => "ISBN",
        'title' => "Título",
        'author' => "Autores"
    );
    const FILTERS = array(
        "title" => "intitle",
        "author" => "inauthor"
    );

    public function getQueryFilters()
    {
        $queryFilters = array();
        foreach (self::FILTERS as $filter => $query) {
            if (isset($_GET[$filter])) {
                $queryFilters[] = "$query:$_GET[$filter]";
            }
        }

        return $queryFilters;
    }

    public function getResultsFound($page)
    {
        $results = array(
            "items" => array(),
            "total_items" => 0
        );
        $params = array(
            "q" => "",
            "printType" => "books",
            "maxResults" => View::PAGINATION['items_per_page'],
            "startIndex" => ($page - 1) * View::PAGINATION['items_per_page']
        );

        $queryFilters = $this->getQueryFilters();
        foreach ($queryFilters as $query) {
            $params["q"] .= " $query";
        }
        $response = $this->call(self::ENDPOINT, $params);

        if (isset($response["items"])) {
            foreach ($response["items"] as $item) {
                $results["items"][] = $this->parseItemInfo($item["volumeInfo"]);
            }
            $results["total_items"] = $response['totalItems'];
        }

        return $results;
    }

    private function parseItemInfo($item)
    {
        return array(
            "doc_id" => $this->getISBN($item),
            "title"  => $item["title"] ?? "-",
            "author" => !empty($item["authors"]) ? implode(", ", $item["authors"]) : "-"
        );
    }

    private function getISBN($item)
    {
        $isbn = array();
        $identifiers = isset($item["industryIdentifiers"]) ? $item["industryIdentifiers"] : array();
        foreach ($identifiers as $identifier) {
            $isbn[] = $identifier["identifier"];
        }

        return !empty($isbn) ? implode(",", $isbn) : '-';
    }

}
