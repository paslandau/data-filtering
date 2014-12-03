<?php
namespace paslandau\DataFiltering\Transformation;
use paslandau\ArrayUtility\ArrayUtil;
use paslandau\DataFiltering\Exceptions\TransformationException;

/**
 * Class ArrayToKeyValueTransformer
 *
 * Transforms data from an array into an associative array.
 * Example
 * [
 *  "key" => "foo"
 *   "value" => "bar"
 * ]
 *
 * ==> $keyIdentifier = "key", $valueIdentifier = "value"
 *
 * ["foo" => "bar"]
 */
class ArrayToKeyValueTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
{

    /**
     * @var int|string
     */
    private $keyIdentifier;

    /**
     * @var int|string
     */
    private $valueIdentifier;

    /**
     * @param int|string $keyIdentifier
     * @param int|string $valueIdentifier
     * @param ArrayTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct($keyIdentifier, $valueIdentifier, ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->keyIdentifier = $keyIdentifier;
        $this->valueIdentifier = $valueIdentifier;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed[] $data
     * @throws \UnexpectedValueException
     * @return mixed[]
     */
    protected function processData(/* array */ $data)
    {
        // todo: throw exception when key/valueIdentifier do no exist?
        $res = array();
        if(!array_key_exists($this->keyIdentifier, $data)){
            throw new TransformationException("Key identifier '{$this->keyIdentifier}' not found in ".ArrayUtil::toString($data));
        }
        $key = $data[$this->keyIdentifier];
        if(!array_key_exists($this->valueIdentifier, $data)){
            throw new TransformationException("Value identifier '{$this->valueIdentifier}' not found in ".ArrayUtil::toString($data));
        }
        $value = $data[$this->valueIdentifier];
        $res[$key] = $value;
        return $res;
    }

}
