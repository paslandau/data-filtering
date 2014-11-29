<?php
namespace paslandau\DataFiltering\Transformation;

class ArraySelectionTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
{

    /**
     * @var mixed[]
     */
    protected $indices;

    /**
     * @var bool|null
     */
    private $ignoreNotExistingIndices;

    /**
     * @param mixed[] $indices
     * @param boolean $ignoreNotExistingIndices . Default: null (false).
     * @param ArrayTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct(array $indices, $ignoreNotExistingIndices = null, ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->indices = $indices;
        if ($ignoreNotExistingIndices === null) {
            $ignoreNotExistingIndices = false;
        }
        $this->ignoreNotExistingIndices = $ignoreNotExistingIndices;
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
        foreach ($this->indices as $key) {
            if (array_key_exists($key, $data)) {
                $res[$key] = $data[$key];
            } elseif (!$this->ignoreNotExistingIndices) {
                $className = (new \ReflectionClass($this))->getShortName();
                throw new \UnexpectedValueException("[$className] Index '$key' is not in the given array and ignoreNotExistingIndices is false");
            }
        }
        return $res;
    }

}
