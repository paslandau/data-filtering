<?php
namespace paslandau\DataFiltering\Extraction;


use paslandau\DataFiltering\Util\StringUtil;
use paslandau\DataFiltering\Events\DataEmitterTrait;
use paslandau\DataFiltering\Transformation\DataTransformerInterface;

abstract class AbstractBaseExtractor implements DataExtractorInterface
{
    use DataEmitterTrait;

    /**
     * @var \paslandau\DataFiltering\Transformation\DataTransformerInterface
     */
    protected $transformer;

    /**
     * @param DataTransformerInterface $transformer [optional]. Default: null.
     */
    function __construct(DataTransformerInterface $transformer = null)
    {
        $this->transformer = $transformer;
        $this->onProcessed = array();
    }

    /**
     * @param mixed|null $data
     * @return mixed
     */
    public function getData($data = null)
    {
        $extracted = $this->extract($data);
        $transformed = $extracted;
        if ($this->transformer !== null) {
            $transformed = $this->transformer->transform($transformed);
        }
        $this->emitProcessedEvent($extracted, $transformed);
        return $transformed;
    }

    /**
     * @param null $data
     * @return null
     */
    protected function extract($data = null)
    {
        return $data;
    }

    /**
     * @return \paslandau\DataFiltering\Transformation\DataTransformerInterface
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * @param \paslandau\DataFiltering\Transformation\DataTransformerInterface $transformer
     */
    public function setTransformer(DataTransformerInterface $transformer = null)
    {
        $this->transformer = $transformer;
    }

    public function __toString(){
        $ss = StringUtil::GetObjectString($this);
        return $ss;
    }
} 