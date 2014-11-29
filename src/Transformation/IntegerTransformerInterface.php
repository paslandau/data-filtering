<?php
namespace paslandau\DataFiltering\Transformation;

interface IntegerTransformerInterface extends DataTransformerInterface{
	
	/**
	 * Transforms $data in something else
	 * @var mixed $data
	 * @return integer 
	 */
	public function transform($data);
	
}
