<?php

namespace App\Model\Api\Datasets;

use App\AZervo;
use App\Model\Api;
use DOMDocument;
use DOMXPath;

class Scihub extends Api
{
    const URL_PREFIXES = array(
        "https://sci-hub.se/",
        "https://sci-hub.ru/"
    );
    const RESULTS_TYPE = array(
        "paper"
    );

    public function getDocumentURL($doi): ?string
    {
        $urls = array();
        foreach (self::URL_PREFIXES as $urlPrefix) {
            $response = $this->call($urlPrefix . $doi, array(), false);
            $dom = new DOMDocument();
            @$dom->loadHTML($response);
            $xpath = new DOMXPath($dom);
            if ($downloadBtn = $xpath->query("//*[@id='buttons']/button")->item(0)) {
                $onClick = $xpath->query("./@onclick", $downloadBtn)->item(0);
                $urls[] = $this->parseLocationHref($onClick->nodeValue, $urlPrefix);
            }
        }

        return $this->urlsToHtml($urls);
    }

    private function parseLocationHref($locationHref, $urlPrefix)
    {
        $suffix = str_replace(array("location.href='/", "location.href=", "'"), "", $locationHref);
        if ($suffix[0] == "/") { # Means that is a full URL, no need to append prefix
            return AZervo::getProtocol() . substr($suffix, 1);
        } else if (strpos($suffix, "http") !== false) {
            return $suffix;
        } else {
            return $urlPrefix . $suffix;
        }
    }

    private function urlsToHtml($urls)
    {
        if (empty($urls)) return "Download [1] <i class='" . Api::ERROR_ICON_CLASS . "'></i>";
        $html = "Download ";
        foreach ($urls as $index => $url) {
            $downloadNum = $index + 1;
            $html .= "<a href='$url'>[$downloadNum]</a> ";
        }

        return $html . " <i class='" . Api::DOWNLOAD_ICON_CLASS . "'></i>";
    }

}