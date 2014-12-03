<?php

use paslandau\DataFiltering\Transformation\DomDocumentTransformer;
use paslandau\DomUtility\DomConverterInterface;

class DomDocumentTransformerTest extends PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
        $mock = $this->getMock(DomConverterInterface::class);
        $mock->expects($this->once())->method("convert");
        /** @var DomConverterInterface $mock */
        $t = new DomDocumentTransformer($mock);
        $t->transform("");
        // convert should have been called exactly once
    }
}
