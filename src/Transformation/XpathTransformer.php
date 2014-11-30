<?php
namespace paslandau\DataFiltering\Transformation;

class XpathTransformer extends AbstractXpathBaseTransformer
{

    /**
     * @param string $xPathExpression
     * @param DomDocumentTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct($xPathExpression, DomDocumentTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        parent::__construct($xPathExpression, $predecessor, $dataCanBeNull);
    }

    /**
     * @param \DOMDocument $data
     * @return string[]
     */
    protected function processData(/* \DOMDocument */
        $data)
    {
        $xpath = new \DOMXPath($data);
        $nodes = @$xpath->query($this->xPathExpression);
        $res = parent::processData($nodes);
        return $res;
    }

}
