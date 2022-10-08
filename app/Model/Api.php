<?php

namespace App\Model;

use App\AZervo;

class Api
{
    const DATASETS_MODELS = array(
        "scihub"
    );

    const ERROR_ICON_CLASS = "fa fa-close";
    const DOWNLOAD_ICON_CLASS = "fa fa-download";
    const DATASETS_PATH = "api_datasets_";

    public function getResultsInDatasets($doi)
    {
        $results = array();
        foreach (self::DATASETS_MODELS as $modelName) {
            $dataset = AZervo::getModel(self::DATASETS_PATH.$modelName);
            $results[$modelName] = $dataset->getDownloadURL($doi);
        }

        return json_encode($results);
    }

    public function call($url, $params = array(), $jsonReturn = true)
    {
        $url = $this->buildGETUrl($url, $params);
        $curl = $this->buildCurl($url);

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        if(!in_array($info['http_code'], array("200", "302"))) {
            var_dump($info);
        }
        if($jsonReturn) $response = json_decode($response, true);
        curl_close($curl);

        return $response;
    }

    private function buildCurl($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        return $curl;
    }

    public function buildGETUrl($baseUrl, $params)
    {

        return empty($params)
            ? $baseUrl
            : $baseUrl . "?" . http_build_query($params);
    }

}