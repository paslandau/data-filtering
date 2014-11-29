<?php

use paslandau\DataFiltering\Transformation\ArrayCountTransformer;

class ArrayCountTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $tests = array(
            [["foo", "bar", "baz", ""], 4],
            [["foo", "bar", new stdClass()], 3],
            [["foo", null], 2],
            [[], 0],
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $expected = $vals[1];
            $t = new ArrayCountTransformer();
            $actual = $t->transform($input);
            $msg = "Expected: $actual == $expected";
            $this->assertEquals($expected, $actual, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            [["foo", "bar", "baz", ""], 5],
            [["foo", "bar", new stdClass()], 0],
            [["foo", null], 3],
            [[], 1],
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $expected = $vals[1];
            $t = new ArrayCountTransformer();
            $actual = $t->transform($input);
            $msg = "Expected: $actual != $expected";
            $this->assertNotEquals($expected, $actual, $msg);
        }
    }
}
