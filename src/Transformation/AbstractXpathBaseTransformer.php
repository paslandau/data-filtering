<?php
namespace paslandau\DataFiltering\Transformation;

abstract class AbstractXpathBaseTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
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
        $this->xPathExpression = $xPathExpression;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var \DOMNodeList|boolean  $data
     * @throws \UnexpectedValueException
     * @return string[]
     */
    protected function processData($data)
    {
        $this->CheckForErrors($data);
        //transform nodelist to array
        $res = iterator_to_array($data, true);
        return $res;
    }

    /**
     * @param \DOMNodeList|boolean $nodes
     * @throws \UnexpectedValueException
     * @return void
     */
    protected function CheckForErrors($nodes)
    {
        if ($nodes === false) {
            $error = error_get_last();
            $msg = "";
            if ($error !== null) {
                $msg = " " . $error['message'];
            }
            throw new \UnexpectedValueException("Invalid xpath expression '{$this->xPathExpression}'.$msg");
        }
    }
}
