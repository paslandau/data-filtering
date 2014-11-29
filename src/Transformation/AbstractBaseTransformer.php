<?php
namespace paslandau\DataFiltering\Transformation;

use paslandau\DataFiltering\Util\StringUtil;
use paslandau\DataFiltering\Events\DataEmitterTrait;

class AbstractBaseTransformer implements DataTransformerInterface
{
    use DataEmitterTrait;

    /**
     * @var boolean
     */
    protected $isCacheActive;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     *
     * @var boolean
     */
    protected $dataCanBeNull;

    /**
     *
     * @var DataTransformerInterface
     */
    protected $predecessor;

    /**
     * @param DataTransformerInterface $predecessor
     * @param boolean $dataCanBeNull . Default: null (false).
     * @param boolean $isCacheActive . Default: null (false).
     */
    public function __construct(DataTransformerInterface $predecessor = null, $dataCanBeNull = null, $isCacheActive = null)
    {
        if ($dataCanBeNull === null) {
            $dataCanBeNull = false;
        }
        $this->dataCanBeNull = $dataCanBeNull;
        $this->predecessor = $predecessor;
        $this->onProcessed = array();
        if ($isCacheActive === null) {
            $isCacheActive = false;
        }
        $this->isCacheActive = $isCacheActive;
        $this->cache = new Cache();
    }

    /**
     * @return DataTransformerInterface
     */
    public function getPredecessor()
    {
        return $this->predecessor;
    }

    /**
     * @param DataTransformerInterface $predecessor
     */
    public function setPredecessor($predecessor)
    {
        $this->predecessor = $predecessor;
    }

    /**
     * @var mixed $data
     * @throws \UnexpectedValueException
     * @return mixed
     */
    public function transform($input)
    {
        if ($this->isCacheActive && $this->cache->contains($input)) {
            $data = $this->cache->getData($input);
            $res = $this->cache->getRes($input);
        } else{
            $data = $input;
            if ($this->predecessor !== null) {
                $data = $this->predecessor->transform($input);
            }

            if ($data === null && !$this->dataCanBeNull) {
                    $className = (new \ReflectionClass($this))->getShortName();
                    throw new \UnexpectedValueException("[$className] data must not be null");
            }
            if($data !== null){
                $res = $this->processData($data); // overriden by subclasses
                $this->fillCache($input, $data, $res);
            } else{
                return null;
            }
        }

        $this->emitProcessedEvent($data, $res);
        return $res;
    }

    /**
     * Override in subclass!
     * @param mixed $data
     * @return mixed
     */
    protected function processData($data)
    {
        return $data;
    }

    /**
     * @param $input
     * @param mixed $data
     * @param mixed $res
     */
    private function fillCache($input, $data, $res)
    {
        if ($this->isCacheActive) {
            $this->cache->addToCache($input, $data, $res);
        }
    }

    /**
     * @param boolean $active
     */
    public function setIsCacheActive($active)
    {
        $this->isCacheActive = $active;
    }

    /**
     * @return boolean
     */
    public function getIsCacheActive()
    {
        return $this->isCacheActive;
    }

    /**
     * Clears the current cache
     * @return void
     */
    public function clearCache()
    {
        $this->cache->clear();
    }

    public function __toString()
    {
        $dontShow = ["predecessor", "onProcessed", "cacheOutputData", "cacheInputData"];
        $ss = StringUtil::GetObjectString($this,$dontShow);
        return $ss;
    }

}
