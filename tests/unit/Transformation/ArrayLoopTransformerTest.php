<?php

use paslandau\DataFiltering\Transformation\ArrayLoopTransformer;
use paslandau\DataFiltering\Transformation\DataTransformerInterface;

class ArrayLoopTransformerTest extends PHPUnit_Framework_TestCase
{

    public function test_transform()
    {
        $tests = [
            "empty-array" => [
                "input" => [],
                "expected" => [],
            ],
            "array" => [
                "input" => ["foo" => "foo", "bar" => "bar", "baz" => "baz"],
                "expected" => ["foo" => 1, "bar" => 1, "baz" => 1],
            ]
        ];
        foreach ($tests as $name => $data) {
            $input = $data["input"];
            $expected = $data["expected"];
            $mock = $this->getMock(DataTransformerInterface::class);
            $mock->expects($this->exactly(count($input)))->method("transform")->will($this->returnValue(1));
            /** @var DataTransformerInterface $mock */
            $t = new ArrayLoopTransformer($mock);
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
