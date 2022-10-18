<?php

namespace App\Model\Api\Datasets;

use App\AZervo;
use App\Model\Api;

class Azervodb extends Api
{
    public function getDownloadURL($doi)
    {
        $links = array();
        if($document = AZervo::getModel('document')->loadByDoi($doi)) {
            $links = array_filter(explode(";", $document['links']));
            $linksStr = "AZervo ";
            $count = 0;
            foreach ($links as $link) {
                $count++;
                $linksStr.= "<a href='$link'>[$count] </a>";
            }
        }
        return !empty($links)
            ? $linksStr
            : "AZervo <i class='".Api::ERROR_ICON_CLASS."'></i>";
    }
}