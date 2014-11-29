<?php
namespace paslandau\DataFiltering\Transformation;

interface BooleanTransformerInterface extends DataTransformerInterface{
	
	/**
	 * Transforms $data in a boolean
	 * @var mixed $data
	 * @return boolean
	 */
	public function transform($data);
}
