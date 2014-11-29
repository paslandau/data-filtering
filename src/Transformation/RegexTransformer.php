<?php
namespace paslandau\DataFiltering\Transformation;

class RegexTransformer extends AbstractBaseTransformer implements ArrayTransformerInterface
{

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var int|string
     */
    protected $groupIndex;

    /**
     * @param string $pattern
     * @param mixed $groupIndex [optional]. Default: null (0).
     * @param StringTransformerInterface $predecessor [optional]. Default: null.
     * @param boolean $dataCanBeNull . [optional]. Default: null (false).
     */
    public function __construct($pattern, $groupIndex = null, StringTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->pattern = $pattern;
        if ($groupIndex === null) {
            $groupIndex = 0;
        }
        $this->groupIndex = $groupIndex;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * preg_match_all on $data with $this->pattern
     * @var string $data
     * @throws \UnexpectedValueException
     * @return string[]
     */
    protected function processData($data)
    {
        $patternRes = @preg_match_all($this->pattern, $data, $matches);
        if ($patternRes === false) {
            $error = error_get_last();
            $msg = "";
            if ($error !== null) {
                $msg = " " . $error['message'];
            }
            throw new \UnexpectedValueException("Invalid pattern pattern: {$this->pattern}.{$msg}");
        }
        $res = array();
        if ($patternRes > 0) {
            if (array_key_exists($this->groupIndex, $matches)) {
                $res = $matches[$this->groupIndex];
            } else {
                throw new \UnexpectedValueException("Group index '{$this->groupIndex}' does not exist in the matched result using pattern '{$this->pattern}''");
            }
        }
        return $res;
    }

}
