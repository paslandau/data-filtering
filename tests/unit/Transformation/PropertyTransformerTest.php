<?php

use paslandau\DataFiltering\Transformation\PropertyTransformer;

class PropertyTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $prop = "foo";
        $expected = "bar";
        $o = new foo_test;
        $o->{$prop} = $expected;
        $t = new PropertyTransformer($prop);
        $actual = $t->transform($o);
        $msg = "Expected: $actual == $expected";
        $this->assertEquals($expected, $actual, $msg);
    }

}

class foo_test
{
    public $foo;
}