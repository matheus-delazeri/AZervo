<?php

namespace App\Model;

use App\AZervo;

class Api
{
    const DATASETS_MODELS = array(
        "scihub",
        "azervodb"
    );

    const ERROR_ICON_CLASS = "fa fa-close";
    const DOWNLOAD_ICON_CLASS = "fa fa-download";
    const DATASETS_PATH = "api_datasets_";

    public function getResultsInDatasets($id, $type)
    {
        $results = array();
        foreach (self::DATASETS_MODELS as $modelName) {
            $dataset = AZervo::getModel(self::DATASETS_PATH.$modelName);
            if(in_array($type, $dataset::RESULTS_TYPE)) {
                $results[$modelName] = $dataset->getDocumentURL($id);
            }
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
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true
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