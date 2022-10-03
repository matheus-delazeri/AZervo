<?php

namespace App\Model\Api\Datasets;

use App\Model\Api;
use DOMXPath;

class Zlibrary extends Api
{
    const ENDPOINT = 'https://pt.booksc.org/s/';

    public function getDownloadURL($doi)
    {
        $url = "";
        $response = $this->call(self::ENDPOINT, array("q" => $doi), false);
        var_dump($response);
        $dom = new \DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DomXPath($dom);
        $linksInPage = $xpath->evaluate('//a[@href]');

        return $url != ""
            ? $url
            : "Não encontrado";

    }

    private function parseLocationHref($htmlElement)
    {
        $url = "";
        if(strpos($htmlElement, "location.href='/") !== false) {
            $suffix = str_replace(array("location.href='/", "'"), "", $htmlElement);
            if($suffix[0] == "/") { # Means that is a full URL, no need to append prefix
                $url = "http://" . substr($suffix, 1);
            } else {
                $url = self::ENDPOINT . $suffix;
            }
        }

        return "<a href='{$url}'>{$url}</a>";
    }

}