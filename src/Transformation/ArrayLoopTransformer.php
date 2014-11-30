<?php
namespace paslandau\DataFiltering\Transformation;

/**
 * Class ArrayLoopTransformer
 * Expects an array as input and loops over each element, using $this->loopTransformer on each element.
 * @package paslandau\DataFiltering\Transformation
 */
class ArrayLoopTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
{

    /**
     * @var DataTransformerInterface
     */
    protected $loopTransformer;

    /**
     * @param DataTransformerInterface $loopTransformer
     * @param ArrayTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct(DataTransformerInterface $loopTransformer, ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->loopTransformer = $loopTransformer;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed[] $data
     * @throws \UnexpectedValueException
     * @return mixed[]
     */
    protected function processData(/* array */
        $data)
    {
        $res = array();
        foreach ($data as $key => $val) {
            $res[$key] = $this->loopTransformer->transform($val);
        }
        return $res;
    }

}
