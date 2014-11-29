<?php
namespace paslandau\DataFiltering\Extraction;

use paslandau\DataFiltering\Events\DataEmitterInterface;
use paslandau\DataFiltering\Transformation\DataTransformerInterface;

interface DataExtractorInterface extends DataEmitterInterface
{

    /**
     * @param mixed $responseData
     * @return mixed
     */
    public function getData($responseData);

    /**
     * @return DataTransformerInterface
     */
    public function getTransformer();

    /**
     * @param DataTransformerInterface $trans
     * @return void
     */
    public function setTransformer(DataTransformerInterface $trans);
}