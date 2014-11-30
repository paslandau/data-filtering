<?php
namespace paslandau\DataFiltering\Transformation;

class DomNodeToStringTransformer extends AbstractBaseTransformer implements StringTransformerInterface
{
    const METHOD_NODE_VALUE = "NodeValue";
    const METHOD_INNER_HTML = "InnerHtml";
    const METHOD_OUTER_HTML = "OuterHtml";

    /**
     *
     * @var string
     */
    protected $method;

    /**
     * @param string $method
     * @param DataTransformerInterface $predecessor [optional]. Default: null.
     * @param boolean $dataCanBeNull [optional]. Default: null (false).
     * @throws \InvalidArgumentException
     */
    public function __construct($method, DataTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $methods = (new \ReflectionClass(__CLASS__))->getConstants();
        if (!in_array($method, $methods)) {
            throw new \InvalidArgumentException("Invalid method '$method'. Possible values " . implode(", ", $methods));
        }
        $this->method = $method;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed $data
     * @return string
     */
    protected function processData(/* \DOMNode */
        $data)
    {
        $res = null;
        switch ($this->method) {
            case self::METHOD_NODE_VALUE: {
                $res = $data->nodeValue;
                break;
            }
            case self::METHOD_INNER_HTML: {
                $childrenHtml = array();
                foreach ($data->childNodes as $child) {
                    $childrenHtml[] = $data->ownerDocument->saveXML($child);
                }
                $res = implode("\n", $childrenHtml);
                break;
            }
            case self::METHOD_OUTER_HTML: {
                $res = $data->ownerDocument->saveXML($data);
                break;
            }
        }
        return $res;
    }
}
