<?php

namespace App\Model\Api\Datasets;

use App\AZervo;
use App\Model\Api;
use DOMDocument;

class Scihub extends Api
{
    const URL_PREFIX = "https://sci-hub.se/";

    public function getDownloadURL($doi): ?string
    {
        $url = "";
        $response = $this->call(self::URL_PREFIX.$doi, array(), false);
        $dom = new DOMDocument();
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
            : "Sci-Hub <i class='".Api::ERROR_ICON_CLASS."'></i>";
    }

    private function parseLocationHref($htmlElement): string
    {
        $url = "";
        if(str_contains($htmlElement, "location.href='/")) {
            $suffix = str_replace(array("location.href='/", "'"), "", $htmlElement);
            if($suffix[0] == "/") { # Means that is a full URL, no need to append prefix
                $url = AZervo::getProtocol() . substr($suffix, 1);
            } else {
                $url = self::URL_PREFIX . $suffix;
            }
        }

        return "<a href='$url'>Sci-Hub <i class='".Api::DOWNLOAD_ICON_CLASS."'></i></a>";
    }

}