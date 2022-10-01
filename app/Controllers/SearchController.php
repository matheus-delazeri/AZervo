<?php

namespace App\Controllers;

use App\AZervo;

class SearchController
{
    public function indexAction()
    {
        echo AZervo::getModel("api")->getBasicResults($_POST["query"]);
    }

    public function resultsAction()
    {
        AZervo::loadView("results");
    }

    public function inDatasetsAction()
    {
        echo AZervo::getModel("api")->getResultsInDatasets($_POST["doi"]);
    }
}