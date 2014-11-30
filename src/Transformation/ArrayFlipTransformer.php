<?php
namespace paslandau\DataFiltering\Transformation;

class ArrayFlipTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
{

    /**
     * @param ArrayTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct(ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed[] $data
     * @throws \UnexpectedValueException
     * @return mixed[]
     */
    protected function processData(/* array */  $data)
    {
        $res = array_flip($data);
        return $res;
    }

}
