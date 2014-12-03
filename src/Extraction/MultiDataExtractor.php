<?php
namespace paslandau\DataFiltering\Extraction;

use paslandau\DataFiltering\Transformation\DataTransformerInterface;

/**
 * Class MultiDataExtractor
 */
class MultiDataExtractor extends AbstractBaseExtractor implements DataExtractorInterface
{

    /**
     * @var DataExtractorInterface[]
     */
    private $extractors;

    /**
     * @param DataExtractorInterface[] $extractors
     * @param DataTransformerInterface $transformer
     */
    function __construct(array $extractors = null, DataTransformerInterface $transformer = null)
    {
        if ($extractors === null) {
            $extractors = array();
        }
        $this->extractors = $extractors;
        parent::__construct($transformer);
    }

    /**
     * @param string $returnIdentifier
     * @param DataExtractorInterface $extractor
     */
    public function addExtractor($returnIdentifier, DataExtractorInterface $extractor)
    {
        $this->extractors[$returnIdentifier] = $extractor;
    }

    /**
     * Removes the $extractor. Can be either string (uses input $extractor the $returnIdentifier) or object of type DataExtractorInterface
     * @param DataExtractorInterface|string $extractor
     */
    public function removeExtractor($extractor)
    {
        if (is_string($extractor)) {
            $this->removeExtractorByReturnIdentifier($extractor);
        }
        if ($extractor instanceof DataExtractorInterface) {
            $this->removeExtractorByExtractor($extractor);
        }
    }

    /**
     * @return \paslandau\DataFiltering\Extraction\DataExtractorInterface[]
     */
    public function getExtractors()
    {
        return $this->extractors;
    }

    /**
     * Extracts the information of $responseData and uses $this->transformer on it (if set).
     * @param mixed|null $data
     * @return mixed[]
     */
    protected function extract($data = null)
    {
        $extracted = array();
        foreach ($this->extractors as $returnIdentifier => $extractor) {
            $res = $extractor->GetData($data);
            $extracted[$returnIdentifier] = $res;
        }
        return $extracted;
    }

    /**
     * @param string $returnIdentifier
     */
    private function removeExtractorByReturnIdentifier($returnIdentifier)
    {
        if (array_key_exists($returnIdentifier, $this->extractors)) {
            unset($this->extractors[$returnIdentifier]);
        }
    }

    /**
     * @param DataExtractorInterface $extractor
     */
    private function removeExtractorByExtractor(DataExtractorInterface $extractor)
    {
        $key = array_search($extractor, $this->extractors);
        if ($key !== false) {
            unset($this->extractors[$key]);
        }
    }
}