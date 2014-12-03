<?php

use paslandau\DataFiltering\Transformation\DomDocumentTransformer;
use paslandau\DataFiltering\Transformation\GenericTransformer;
use paslandau\DomUtility\DomConverterInterface;

class GenericTransformerTest extends PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
        $f = function ($data){
            return $data;
        };
        $t = new GenericTransformer($f);
        $values = [
            true,
            "foo",
            new stdClass(),
            ["bar"],
            4711
        ];
        foreach($values as $val) {
            $actual = $t->transform($val);
            $this->assertSame($val,$actual);
        }
    }
}
