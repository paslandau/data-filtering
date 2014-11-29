<?php
namespace paslandau\DataFiltering\Transformation;

use paslandau\ArrayUtility\ArrayPath\ArraySelector;

class ArrayMergeTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
{

    /**
     * @var ArrayMergingDescriptor[]
     */
    protected $mergingDescriptors;

    /**
     * @param ArrayMergingDescriptor[] $mergingDescriptors
     * @param ArrayTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct(array $mergingDescriptors, ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->mergingDescriptors = $mergingDescriptors;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed[] $data
     * @throws \UnexpectedValueException
     * @return mixed[]
     */
    protected function processData(array $data)
    {
        $targetArr = new ArraySelector([]);
        $inputArr = new ArraySelector($data);
        foreach ($this->mergingDescriptors as $descriptor) {
            $descriptor->merge($inputArr, $targetArr);
        }
        $res = $targetArr->getArray();
        return $res;
    }

}
