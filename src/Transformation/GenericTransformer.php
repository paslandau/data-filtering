<?php
namespace paslandau\DataFiltering\Transformation;

class GenericTransformer extends AbstractBaseTransformer implements DataTransformerInterface
{

    /**
     * @var callable
     */
    protected $func;

    /**
     * @param callable $func
     * @param DataTransformerInterface $predecessor
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct(callable $func, DataTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->func = $func;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed $data
     * @return mixed
     */
    protected function processData($data)
    {
        $f = $this->func;
        $res = $f($data);
        return $res;
    }

}
