<?php
namespace paslandau\DataFiltering\Evaluation;

use paslandau\ComparisonUtility\ComparisonObjectInterface;
use paslandau\DataFiltering\Events\DataEmitterTrait;
use paslandau\DataFiltering\Extraction\DataExtractorInterface;
use paslandau\DataFiltering\Util\StringUtil;

class DataEvaluator implements DataEvaluatorInterface
{
    use DataEmitterTrait;
    /**
     *
     * @var ComparisonObjectInterface
     */
    private $comparison;

    /**
     *
     * @var DataExtractorInterface
     */
    private $extractor;

    public function __construct(ComparisonObjectInterface $comparison, DataExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
        $this->comparison = $comparison;
    }

    /**
     * @param mixed $input
     * @return bool
     */
    public function solve($input = null)
    {
        $extracted = $this->extractor->GetData($input);
        $result = $this->comparison->CompareToExpected($extracted);
        $this->emitProcessedEvent($extracted, $result);
        return $result;
    }

    /**
     * @return \paslandau\ComparisonUtility\ComparisonObjectInterface
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * @return \paslandau\DataFiltering\Extraction\DataExtractorInterface
     */
    public function getExtractor()
    {
        return $this->extractor;
    }

    public function __toString()
    {
        $ss = StringUtil::GetObjectString($this);
        return $ss;
    }
}