<?php

use paslandau\DataFiltering\Exceptions\TransformationException;
use paslandau\DataFiltering\Transformation\ArraySelectionTransformer;

class ArraySelectionTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $tests = array(
            [["foo", "bar", "baz", ""], [0], ["foo"]],
            [["foo", "bar", "baz", ""], [1, 2], ["bar", "baz"]],
            [["foo", "bar", "baz", ""], [1, 2, 10], ["bar", "baz"]],
            [["foo", "test" => "bar", "baz", ""], ["test"], ["bar"]]
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $indices = $vals[1];
            $expected = $vals[2];
            $t = new ArraySelectionTransformer($indices, true);
            $actual = $t->transform($input);

            $indexStr = implode(", ", $indices);
            $count = count($expected);
            $msg = "Expected Count: " . count($actual) . " == $count using $indexStr";
            $this->assertCount($count, $actual, $msg);
            $str = implode(", ", $actual);
            $expected = implode(", ", $expected);
            $msg = "Expected: $str == $expected using $indexStr";
            $this->assertEquals($expected, $str, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            [["foo", "bar", "baz", ""], [0], ["bar"]],
            [["foo", "bar", "baz", ""], [1, 2], ["foo", "bar"]],
            [["foo", "bar", "baz", ""], [1, 2, 10], ["bar", "baz", ""]],
            [["foo", "test" => "bar", "baz", ""], [1], ["bar"]]
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $indices = $vals[1];
            $expected = $vals[2];
            $t = new ArraySelectionTransformer($indices, true);
            $actual = $t->transform($input);

            $indexStr = implode(", ", $indices);
            $count = count($expected);
            $count++;
            $msg = "Expected Count: " . count($actual) . " != $count using $indexStr";
            $this->assertNotCount($count, $actual, $msg);
            $str = implode(", ", $actual);
            $expected = implode(", ", $expected);
            $msg = "Expected: $str != $expected using $indexStr";
            $this->assertNotEquals($expected, $str, $msg);
        }
    }

    public function testIndexOutOfRange()
    {
        $this->setExpectedException(TransformationException::class);
        $tests = array(
            [["foo", "bar", "baz", ""], [1, 2, 10]],
        );
        foreach ($tests as $vals) {
            $input = $vals[0];
            $indices = $vals[1];
            $t = new ArraySelectionTransformer($indices, false);
            $t->transform($input);
        }
    }
}
