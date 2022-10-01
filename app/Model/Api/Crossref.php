<?php

namespace App\Model\Api;

use App\Model\Api;

class Crossref extends Api
{
    const ENDPOINT = "http://api.crossref.org/works";

    public function getResultsFound($query)
    {
        $results = array();
        $response = $this->call(self::ENDPOINT, array("query" => $query, "rows" => 20));
        $items = $response["message"]["items"];
        foreach ($items as $item) {
            $results[$item["DOI"]] = array(
                "title" => isset($item["title"]) ? $item["title"][0] : $item["description"],
                "type"  => $item["type"]
            );
        }

        return $results;
    }
}