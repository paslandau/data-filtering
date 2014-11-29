<?php
namespace paslandau\DataFiltering\Transformation;

interface ArrayTransformerInterface extends DataTransformerInterface{
	
	/**
	 * Transforms $data in something else
	 * @var mixed $data
	 * @return mixed[] 
	 */
	public function transform($data);
	
}
