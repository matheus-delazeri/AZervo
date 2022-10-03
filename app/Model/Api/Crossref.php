<?php

namespace App\Model\Api;

use App\Model\Api;

class Crossref extends Api
{
    const ENDPOINT = "http://api.crossref.org/works";

    public function getResultsFound($query, $filter)
    {
        $results = array();
        $response = $this->call(self::ENDPOINT, array("query.$filter" => $query, "rows" => 20));
        $items = $response["message"]["items"];
        foreach ($items as $item) {
            $results[$item["DOI"]] = array(
                "title" => isset($item["title"]) ? $item["title"][0] : $item["description"],
                "type"  => $item["type"],
                "author" => $this->getAuthorsAsStr($item["author"])
            );
        }

        return $results;
    }

    private function getAuthorsAsStr($authorsArr)
    {
        $authorsStrArr = array();
        foreach ($authorsArr as $author) {
            $authorName = "";
            if(isset($author["given"])) {
                $firstname = $author["given"];
                $authorName .= $firstname;
            }
            if($author["family"]) {
                $lastname = $author["family"];
                $authorName .= " ".$lastname;
            }
            if($authorName) {
                $authorsStrArr[] = $authorName;
            }
        }

        return implode(", ", $authorsStrArr);
    }
}