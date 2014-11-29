<?php
namespace paslandau\DataFiltering\Transformation;

class ArraySelectSingleTransformer extends ArraySelectionTransformer implements DataTransformerInterface{

    /**
     * @param mixed $index
     * @param boolean $ignoreNotExistingIndices [optional]. Default: null. @see ArraySelectionTransformer
     * @param ArrayTransformerInterface $predecessor [optional]. Default: null.
     * @param boolean $dataCanBeNull [optional]. Default: null. @see AbstractBaseTransformer
     */
    public function __construct($index, $ignoreNotExistingIndices = null, ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null){
        parent::__construct([$index], $ignoreNotExistingIndices, $predecessor, $dataCanBeNull);
	}
	
	/**
	 * @var mixed[] $data
	 * @return mixed
	 */
	protected function processData(array $data){
        $data = parent::processData($data);
        if(count($data) == 0){
            return null;
        }
        $res = reset($data);
        return $res;
	}
	
}
