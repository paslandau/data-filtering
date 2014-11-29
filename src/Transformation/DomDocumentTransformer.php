<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 29.08.14
 * Time: 09:58
 */

namespace paslandau\DataFiltering\Transformation;


use paslandau\DomUtility\DomConverterInterface;

class DomDocumentTransformer extends AbstractBaseTransformer implements DomDocumentTransformerInterface
{
    /**
     *
     * @var DomConverterInterface
     */
    protected $domConverter;

    /**
     * @param DomConverterInterface $domConverter
     * @param StringTransformerInterface $predecessor . Default: null.
     * @param boolean $dataCanBeNull . Default: null (false).
     */
    public function __construct(DomConverterInterface $domConverter, StringTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->domConverter = $domConverter;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var string $data
     * @return \DOMDocument
     */
    protected function processData($data)
    {
        $res = $this->domConverter->Convert($data);
        return $res;
    }
}