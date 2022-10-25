<?php

namespace App\Controllers;

use App\AZervo;

class SearchController
{
    public function resultsAction()
    {
        AZervo::loadView("results");
    }

    public function inDatasetsAction()
    {
        echo AZervo::getModel("api")->getResultsInDatasets($_POST["id"], $_POST["type"]);
    }

}