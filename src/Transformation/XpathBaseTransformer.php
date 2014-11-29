<?php
namespace paslandau\DataFiltering\Transformation;

class XpathBaseTransformer extends AbstractXpathBaseTransformer
{
    /**
     *
     * @var string
     */
    protected $xPathExpression;

    /**
     * @param string $xPathExpression
     * @param DataTransformerInterface $predecessor [optional]. Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct($xPathExpression, DataTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        parent::__construct($xPathExpression, $predecessor, $dataCanBeNull);
    }

    /**
     * @var \DOMNode|\DOMDocument $data
     * @throws \UnexpectedValueException
     * @return \DOMNode[]
     */
    protected function processData($data)
    {

        if($data instanceof \DOMDocument) {
            $doc = $data;
            $node = null;
        }elseif($data instanceof \DomNode) {
            $node = $data;
            $doc = $node->ownerDocument;
        }else{
            throw new \InvalidArgumentException("data must be of type DomNode or DomDocument");
        }
        $xpath = new \DOMXPath($doc);
        $nodes = @$xpath->query($this->xPathExpression, $node);
        $res = parent::processData($nodes);
        return $res;
    }
}
