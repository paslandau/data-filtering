<?php

namespace paslandau\DataFiltering\Extraction;


use paslandau\DataFiltering\Transformation\DataTransformerInterface;

class InputDataExtractor extends AbstractBaseExtractor
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @param mixed $data [optional]. Default: null;
     * @param DataTransformerInterface $transformer [optional]. Default: null;
     */
    function __construct($data = null, DataTransformerInterface $transformer = null)
    {
        $this->data = $data;
        parent::__construct($transformer);
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function extract($data)
    {
        return $this->data;
    }
}