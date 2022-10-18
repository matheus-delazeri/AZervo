<?php

namespace App\Model;

use App\AZervo;

class Document extends Core
{
    public function add($doi, $links)
    {
        $newDocument = array(
            'doi' => $doi,
            'links' => implode(';', $links)
        );
        return $this->addNewRegister('documents', $newDocument);
    }

    public function load($id)
    {
        return $this->getRegisterById('documents', $id);
    }

    public function loadByDoi($doi)
    {
        return $this->getRegisterByUniqueField('documents', 'doi', $doi);
    }

}
