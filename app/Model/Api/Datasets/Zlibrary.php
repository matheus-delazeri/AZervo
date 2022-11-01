<?php

namespace App\Model\Api\Datasets;

use App\Model\Api;
use DOMDocument;
use DOMXPath;

class Zlibrary extends Api
{
    const URL_PREFIX = "https://b-ok.lat/";
    const RESULTS_TYPE = array(
        "book"
    );
    const LANGUAGES = array(
        "portuguese",
        "english",
        "spanish"
    );

    public function getDocumentURL($isbn)
    {
        if(strpos($isbn, ":")!== False) return "Z-Library <i class='" . Api::ERROR_ICON_CLASS . "'></i>"; # Isn't ISBN
        $url = "";
        $params = array(
            "q" => $isbn,
            "languages" => self::LANGUAGES
        );
        $response = $this->call(self::URL_PREFIX."s/", $params, false);
        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);
        $bookRows = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'bookRow')]");
        if($book = $bookRows->item(0)) {
            $link = $xpath->query("./td[2]/table/tr/td/h3/a/@href" , $book);
            $url = $this->parseHref($link->item(0)->nodeValue);
        }

        return $url != ""
            ? $url
            : "Z-Library <i class='" . Api::ERROR_ICON_CLASS . "'></i>";
    }

    private function parseHref($externalLink)
    {
        $url = self::URL_PREFIX . substr($externalLink, 1);
        return "<a target='_blank' href='$url'>Z-Library <i class='" . Api::EXTERNAL_ICON_CLASS . "'></i></a>";
    }

}
