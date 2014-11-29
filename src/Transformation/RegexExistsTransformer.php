<?php
namespace paslandau\DataFiltering\Transformation;

class RegexExistsTransformer extends RegexTransformer implements BooleanTransformerInterface{

    /**
     * @param string $pattern
     * @param int|string $groupIndex [optional]. Default: null.
     * @param StringTransformerInterface $predecessor [optional]. Default: null.
     * @param null $dataCanBeNull [optional]. Default: null.
     */
    public function __construct($pattern, $groupIndex = null, StringTransformerInterface $predecessor = null, $dataCanBeNull = null){
        parent::__construct($pattern, $groupIndex, $predecessor, $dataCanBeNull);
	}

    /**
     * preg_match_all on $data with $this->pattern
     * @var string $data
     * @throws \UnexpectedValueException
     * @return string[]
     */
    protected function processData($data)
    {
        $data = parent::processData($data);
        $res = (count($data) > 0);
        return $res;
    }
}
