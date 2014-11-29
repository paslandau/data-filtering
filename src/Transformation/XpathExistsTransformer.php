<?php
namespace paslandau\DataFiltering\Transformation;

class XpathExistsTransformer extends XpathBaseTransformer implements BooleanTransformerInterface{

    /**
     * @param string $xPathExpression
     * @param DomDocumentTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct($xPathExpression, DomDocumentTransformerInterface $predecessor = null, $dataCanBeNull = null){
        parent::__construct($xPathExpression, $predecessor, $dataCanBeNull);
	}

    /**
     * @var \DOMDocument|\DOMNode $data
     * @throws \UnexpectedValueException
     * @return boolean
     */
    protected function processData($data)
    {
        $res = parent::processData($data);
        return (count($res) > 0);
    }
}
