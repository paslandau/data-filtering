<?php
namespace paslandau\DataFiltering\Transformation;

use paslandau\DataFiltering\Events\DataEmitterInterface;

interface DataTransformerInterface extends DataEmitterInterface{
	
	/**
	 * Transforms $data in something else
	 * @var mixed $data
	 * @return mixed 
	 */
	public function transform($data);

    /**
     * @return DataTransformerInterface
     */
    public function getPredecessor();

    /**
     * @param DataTransformerInterface $predecessor
     */
    public function setPredecessor($predecessor);

    /**
     * @param boolean $active
     * @return void
     */
    public function setIsCacheActive($active);

    /**
     * @return boolean
     */
    public function getIsCacheActive();
    /**
     * Clears the current cache
     * @return void
     */
    public function clearCache();
}
