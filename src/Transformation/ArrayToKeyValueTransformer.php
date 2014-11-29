<?php
namespace paslandau\DataFiltering\Transformation;

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
    protected function processData(array $data)
    {
        $res = array();
        $key = array_key_exists($this->keyIdentifier, $data) ? $data[$this->keyIdentifier] : 0;
        $value = array_key_exists($this->valueIdentifier, $data) ? $data[$this->valueIdentifier] : null;
        $res[$key] = $value;
        return $res;
    }

}
