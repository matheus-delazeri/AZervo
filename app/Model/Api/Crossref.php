<?php

namespace App\Model\Api;

use App\Model\Api;

class Crossref extends Api
{
    const ENDPOINT = "http://api.crossref.org/works";
    const ITEMS_PER_PAGE = 5;
    const MAX_ITEMS = 9990;
    const FILTERS = array(
        "title" => "Título",
        "author" => "Autor"
    );

    public function getQueryFilters()
    {
        $queryFilters = array();
        foreach (self::FILTERS as $filter => $label) {
            if (isset($_GET[$filter])) {
                $queryFilters["query.$filter"] = $_GET[$filter];
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
            "sort" => "score",
            "order" => "desc",
            "select" => "DOI,title,author,type,subject,ISBN",
            "rows" => self::ITEMS_PER_PAGE,
            "offset" => ($page - 1) * self::ITEMS_PER_PAGE
        );

        $queryFilters = $this->getQueryFilters();
        foreach ($queryFilters as $filter => $query) {
            $params[$filter] = $query;
        }
        $response = $this->call(self::ENDPOINT, $params);
        if ($items = $response["message"]["items"]) {
            foreach ($items as $item) {
                $results["items"][] = $this->parseItemInfo($item);
            }
            $totalResults = $response["message"]["total-results"];
            $results["total_items"] = min($totalResults, self::MAX_ITEMS);
        }

        return $results;
    }

    public function getItemByDoi($doi)
    {
        $response = $this->call(self::ENDPOINT."/$doi", array());
        if($item = $response['message']) {
            return $this->parseItemInfo($item);
        }

        return false;
    }

    private function parseItemInfo($item)
    {
        return array(
            "doi" => $item["DOI"] ?? "-",
            "title" => isset($item["title"]) ? $item["title"][0] : "-",
            "type" => $item["type"] ?? "-",
            "subject" => isset($item["subject"]) ? implode(", ", $item["subject"]) : "-",
            "isbn" => isset($item["ISBN"]) ? implode(", ", $item["ISBN"]) : "-",
            "author" => $this->getAuthorsAsStr($item)
        );
    }

    private function getAuthorsAsStr($item)
    {
        $authorsStrArr = array();
        if (isset($item["author"])) {
            $authorsArr = $item["author"];
            foreach ($authorsArr as $author) {
                $authorName = "";
                if (isset($author["given"])) {
                    $firstname = $author["given"];
                    $authorName .= $firstname;
                }
                if (isset($author["family"])) {
                    $lastname = $author["family"];
                    $authorName .= " " . $lastname;
                }
                if ($authorName) {
                    $authorsStrArr[] = $authorName;
                }
            }
        }

        return !empty($authorsStrArr) ? implode(", ", $authorsStrArr) : "-";
    }
}