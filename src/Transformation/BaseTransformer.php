<?php
namespace paslandau\DataFiltering\Transformation;

class BaseTransformer extends AbstractBaseTransformer
{
    /**
     * @param DataTransformerInterface $predecessor
     * @param boolean $dataCanBeNull . Default: null (false).
     * @param boolean $isCacheActive . Default: null (false).
     */
    public function __construct(DataTransformerInterface $predecessor = null, $dataCanBeNull = null, $isCacheActive = null)
    {
        parent::__construct($predecessor, $dataCanBeNull, $isCacheActive);
    }


}
