<?php
namespace paslandau\DataFiltering\Transformation;

class ArrayCountTransformer extends AbstractBaseTransformer implements IntegerTransformerInterface
{

    /**
     * @param ArrayTransformerInterface $predecessor
     * @param boolean $dataCanBeNull [optional]. Default: null (false).
     */
    public function __construct(ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed[] $data
     * @return integer
     */
    protected function processData(array $data)
    {
        $res = count($data);
        return $res;
    }

}
