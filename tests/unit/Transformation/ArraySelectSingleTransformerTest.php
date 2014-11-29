<?php

use paslandau\DataFiltering\Transformation\ArraySelectSingleTransformer;

class ArraySelectSingleTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $tests = array(
            [["foo", "bar", "baz", ""], 0, "foo"],
            [["foo", "bar", "baz", ""], 1, "bar"],
            [["foo", "bar", "baz", ""], 10, null],
            [["foo", "bar", "baz", ""], 10, ""],
            [["foo", "bar", "baz", ""], 10, false],
            [["foo", "test" => "bar", "baz", ""], "test", "bar"]
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $index = $vals[1];
            $expected = $vals[2];
            $t = new ArraySelectSingleTransformer($index, true);
            $actual = $t->transform($input);
            $msg = "Expected: $actual == $expected using $index";
            $this->assertEquals($expected, $actual, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            [["foo", "bar", "baz", ""], 0, "bar"],
            [["foo", "bar", "baz", ""], 1, "baz"],
            [["foo", "bar", "baz", ""], 10, "not null"],
            [["foo", "test" => "bar", "baz", ""], 1, "bar"]
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $index = $vals[1];
            $expected = $vals[2];
            $t = new ArraySelectSingleTransformer($index, true);
            $actual = $t->transform($input);
            $msg = "Expected: $actual != $expected using $index";
            $this->assertNotEquals($expected, $actual, $msg);
        }
    }

    public function testIndexOutOfRange()
    {
        $this->setExpectedException(get_class(new UnexpectedValueException ()));
        $tests = array(
            [["foo", "bar", "baz", ""], 10],
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $index = $vals[1];
            $t = new ArraySelectSingleTransformer($index, false);
            $t->transform($input);
        }
    }
}
