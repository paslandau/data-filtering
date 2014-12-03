<?php
namespace paslandau\DataFiltering\Transformation;

use paslandau\DataFiltering\Transformation\Types\HtmlForm;
use paslandau\DataFiltering\Transformation\Types\HtmlFormInterface;

class HtmlFormTransformer extends AbstractBaseTransformer implements HtmlFormTransformerInterface
{
    /**
     * @param DataTransformerInterface $predecessor [optional]. Default: null.
     * @param null $dataCanBeNull [optional]. Default: null.
     */
    public function __construct(DataTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var \DOMNode $data
     * @return HtmlFormInterface
     */
    protected function processData(/* \DOMNode */ $data)
    {
        $res = HtmlForm::fromDomNode($data);
        return $res;
    }
}
