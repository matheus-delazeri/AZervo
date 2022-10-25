<?php

namespace App\Model;

use App\AZervo;

class Document extends Core
{
    const ATTRIBUTES = array(
        'doc_id' => "ID",
        'title' => "Título",
        'author' => "Autores"
    );

    public function __construct()
    {
        parent::__construct();
        parent::connect();
    }

    public function add($document)
    {
        return $this->addNewRegister('documents', $document);
    }

    public function load($id)
    {
        return $this->getRegisterById('documents', $id);
    }

    public function loadByDocId($docId)
    {
        return $this->getRegisterByUniqueField('documents', 'doc_id', $docId);
    }

}
