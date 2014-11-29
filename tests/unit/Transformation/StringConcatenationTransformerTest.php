<?php

use paslandau\DataFiltering\Transformation\StringConcatenationTransformer;

class StringConcatenationTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $tests = array(
            [" ", ["foo", "bar"], "foo bar"],
            [" ", ["foo"], "foo"],
            ["\n", ["foo", "bar"], "foo\nbar"],
            [" ", [], ""],
        );
        foreach ($tests as $vals) {
            $glue = $vals[0];
            $input = $vals[1];
            $expected = $vals[2];
            $t = new StringConcatenationTransformer($glue);
            $actual = $t->transform($input);
            $msg = "Expected: $actual == $expected using glue '$glue'";
            $this->assertEquals($expected, $actual, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            [" ", ["foo", "bar"], "foo bar "],
            [" ", ["foo"], "foo "],
            ["\n", ["foo", "bar"], "\nfoo\nbar"],
            [" ", [], " "],
        );
        foreach ($tests as $vals) {
            $glue = $vals[0];
            $input = $vals[1];
            $expected = $vals[2];
            $t = new StringConcatenationTransformer($glue);
            $actual = $t->transform($input);
            $msg = "Expected: $actual != $expected using glue '$glue'";
            $this->assertNotEquals($expected, $actual, $msg);
        }
    }
}
