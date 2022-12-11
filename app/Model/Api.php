<?php

namespace App\Model;

use App\AZervo;

class Api
{
    protected $_curl;
    const DATASETS_MODELS = array(
        "scihub",
        "azervodb"
    );

    const ERROR_ICON_CLASS = "fa fa-close";
    const DOWNLOAD_ICON_CLASS = "fa fa-download";
    const DATASETS_PATH = "api_datasets_";

    public function __construct()
    {
        $this->_curl = curl_init();
    }

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
        $this->buildCurl($url);

        $response = curl_exec($this->_curl);
        if($jsonReturn) $response = json_decode($response, true);

        return $response;
    }

    private function buildCurl($url)
    {
        curl_setopt_array($this->_curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
        ));
    }

    public function buildGETUrl($baseUrl, $params)
    {

        return empty($params)
            ? $baseUrl
            : $baseUrl . "?" . http_build_query($params);
    }

}