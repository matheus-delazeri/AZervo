<?php

namespace App\Model\Api\Datasets;

use App\AZervo;
use App\Model\Api;
use DOMDocument;
use DOMXPath;

class Scihub extends Api
{
    const URL_PREFIX = "https://sci-hub.se/";
    const RESULTS_TYPE = array(
        "paper"
    );

    public function getDocumentURL($doi): ?string
    {
        $url = "";
        $response = $this->call(self::URL_PREFIX.$doi, array(), false);
        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);
        if($downloadBtn = $xpath->query("//*[@id='buttons']/button")->item(0)) {
            $onClick = $xpath->query("./@onclick", $downloadBtn)->item(0);
            $url = $this->parseLocationHref($onClick->nodeValue);
        }

        return $url != ""
            ? $url
            : "Sci-Hub <i class='".Api::ERROR_ICON_CLASS."'></i>";
    }

    private function parseLocationHref($locationHref)
    {
        $suffix = str_replace(array("location.href='/", "'"), "", $locationHref);
        if($suffix[0] == "/") { # Means that is a full URL, no need to append prefix
            $url = AZervo::getProtocol() . substr($suffix, 1);
        } else {
            $url = self::URL_PREFIX . $suffix;
        }

        return "<a href='$url'>Sci-Hub <i class='".Api::DOWNLOAD_ICON_CLASS."'></i></a>";
    }

}