<?php
namespace paslandau\DataFiltering\Transformation;

class PropertyTransformer extends AbstractBaseTransformer implements DataTransformerInterface, StringTransformerInterface
{

    /**
     *
     * @var string
     */
    protected $propertyName;

    /**
     * @param string $propertyName
     * @param DataTransformerInterface $predecessor
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct($propertyName, DataTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->propertyName = $propertyName;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var mixed $data
     * @return mixed
     */
    protected function processData($data)
    {
        $class = new \ReflectionClass($data);
        $prop = $class->getProperty($this->propertyName);
        $prop->setAccessible(true);
        $res = $prop->getValue($data);
        return $res;
    }

}
