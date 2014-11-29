<?php
namespace paslandau\DataFiltering\Transformation;

interface DomDocumentTransformerInterface extends DataTransformerInterface{
	
	/**
	 * Transforms $data in something else
	 * @var string $data
	 * @return \DOMDocument
	 */
	public function transform($data);
	
}
