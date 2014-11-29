<?php
namespace paslandau\DataFiltering\Transformation;

class StringConcatenationTransformer extends AbstractBaseTransformer implements StringTransformerInterface{

    /**
     * @var DataTransformerInterface
     */
    protected $glue;

    /**
     * @param string $glue
     * @param ArrayTransformerInterface $predecessor
     * @param boolean $dataCanBeNull. Default: null (false).
     */
    public function __construct($glue, ArrayTransformerInterface $predecessor = null, $dataCanBeNull = null){
		$this->glue = $glue;
		parent::__construct($predecessor, $dataCanBeNull);
	}
	
	/**
	 * Transforms an array of $data in a concatenated string.
	 * 
	 * @var string[] $data
	 * @return string
	 */
	protected function processData($data) {
        $res = implode ( $this->glue, $data );
		return $res;
	}
	
}
