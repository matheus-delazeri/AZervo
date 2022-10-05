<?php

namespace App\Model;

use App\AZervo;

class Api
{
    public $DATASETS_MODELS = array(
        "scihub"
    );

    const DATASETS_PATH = "api_datasets_";

    public function getResultsInDatasets($doi)
    {
        $results = array();
        foreach ($this->DATASETS_MODELS as $modelName) {
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
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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