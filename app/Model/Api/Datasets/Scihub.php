<?php

namespace App\Model\Api\Datasets;

use App\Model\Api;

class Scihub extends Api
{
    const URL_PREFIX = "https://sci-hub.se/";

    public function getDownloadURL($doi)
    {
        $url = "";
        $response = $this->call(self::URL_PREFIX.$doi, array(), false);
        $dom = new \DOMDocument();
        @$dom->loadHTML($response);

        foreach ($dom->getElementsByTagName("button") as $button) {
            foreach ($button->attributes as $attribute) {
                if($url = $attribute->textContent) {
                    $url = $this->parseLocationHref($url);
                }
            }
        }

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
                $url = self::URL_PREFIX . $suffix;
            }
        }

        return "<a href='{$url}'>{$url}</a>";
    }

}