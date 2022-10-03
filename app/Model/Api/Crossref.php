<?php

namespace App\Model\Api;

use App\Model\Api;

class Crossref extends Api
{
    const ENDPOINT = "http://api.crossref.org/works";

    public function getResultsFound($query, $filter)
    {
        $results = array();
        $params = array(
            "query.$filter" => $query,
            "sort" => "score",
            "order" => "desc"
        );
        $response = $this->call(self::ENDPOINT, $params);
        $items = $response["message"]["items"];
        foreach ($items as $item) {
            $results[$item["DOI"]] = array(
                "doi" => $item["DOI"],
                "title" => isset($item["title"]) ? $item["title"][0] : $item["description"],
                "type" => $item["type"],
                "author" => $this->getAuthorsAsStr($item)
            );
        }

        return $results;
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

        return implode(", ", $authorsStrArr);
    }
}
