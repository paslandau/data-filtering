<?php
namespace paslandau\DataFiltering\Transformation;

interface StringTransformerInterface extends DataTransformerInterface{
	
	/**
	 * Transforms $data in something else
	 * @var mixed $data
	 * @return string 
	 */
	public function transform($data);
	
}
