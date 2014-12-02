<?php

use paslandau\DataFiltering\Transformation\ArrayFlipTransformer;

class ArrayFlipTransformerTest extends PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
        $tests = [
            "empty-array" => [
                "input" => [],
                "expected" => [],
            ],
            "array" => [
                "input" => [1 => "foo", 2 => "bar", 3 => "baz"],
                "expected" => ["foo" => 1, "bar" => 2, "baz" => 3],
            ]
        ];
        foreach ($tests as $name => $data) {
            $input = $data["input"];
            $expected = $data["expected"];
            $t = new ArrayFlipTransformer();
            $actual = $t->transform($input);
            $msg = [
                $name,
                "Input   : " . json_encode($input),
                "Expected: " . json_encode($expected),
                "Actual  : " . json_encode($actual),
            ];
            $msg = implode("\n", $msg);
            $this->assertEquals($expected, $actual, $msg);
        }
    }
}
