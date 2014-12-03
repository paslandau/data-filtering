<?php
namespace paslandau\DataFiltering\Transformation;

use paslandau\DataFiltering\Exceptions\TransformationException;
use paslandau\JsonUtility\JsonPathInterface;

class JsonTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
{
    /**
     *
     * @var string
     */
    protected $expression;
    /**
     *
     * @var JsonPathInterface
     */
    protected $jsonPath;

    /**
     * @param string $expression
     * @param JsonPathInterface $jsonPath
     * @param StringTransformerInterface $predecessor [optional]. Default: null.
     * @param boolean $dataCanBeNull [optional]. Default: null (false).
     */
    public function __construct($expression, JsonPathInterface $jsonPath, StringTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->expression = $expression;
        $this->jsonPath = $jsonPath;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var string $data
     * @throws \UnexpectedValueException
     * @return string[]
     */
    protected function processData($data)
    {
        //Convert to assoc JSON
        $json = json_decode($data, true);
        if (!$json) {
            throw new TransformationException("Invalid JSON input");
        }
        // TODO what happens if jsonpath is invalid?
        $res = $this->jsonPath->query($json, $this->expression);
        return $res;
    }

}
