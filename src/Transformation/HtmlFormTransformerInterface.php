<?php
namespace paslandau\DataFiltering\Transformation;

use paslandau\DataFiltering\Transformation\Types\HtmlFormInterface;

interface HtmlFormTransformerInterface extends DataTransformerInterface{

    /**
     * Transforms $data in an HtmlFormInterface
     * @var \DomNode $data
     * @return HtmlFormInterface
     */
    public function transform($data);

}