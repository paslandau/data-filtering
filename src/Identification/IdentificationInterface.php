<?php

namespace paslandau\DataFiltering\Identification;

interface IdentificationInterface {

    /**
     * @param mixed $data
     * @return boolean
     */
    public function isIdentifiedBy($data);

    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @return string[]
     */
    public function getParentIdentifiers();
} 