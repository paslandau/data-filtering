<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 27.08.14
 * Time: 15:26
 */

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