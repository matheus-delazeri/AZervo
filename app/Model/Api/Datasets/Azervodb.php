<?php

namespace App\Model\Api\Datasets;

use App\AZervo;
use App\Model\Api;

class Azervodb extends Api
{
    const RESULTS_TYPE = array(
        "paper",
        "book"
    );

    public function getDocumentURL($docId)
    {
        $links = array();
        if($document = AZervo::getModel('document')->loadByDocId($docId)) {
            $links = array_filter(explode(";", $document['links']));
        }
        return !empty($links)
            ? "<a href='$links[0]'>Download [2] <i class='".Api::DOWNLOAD_ICON_CLASS."'></i></a>"
            : "Download [2] <i class='".Api::ERROR_ICON_CLASS."'></i>";
    }
}
