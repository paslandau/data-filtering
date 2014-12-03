<?php

use paslandau\DataFiltering\Transformation\ArrayToKeyValueTransformer;

class ArrayToKeyValueTransformerTest extends PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
        $tests = [
            "empty-array" => [
                "input" => [],
                "expected" => RuntimeException::class,
            ],
            "array-no-key" => [
                "input" => ["key-not-there" => "foo", "value" => "bar"],
                "expected" => RuntimeException::class,
            ],
            "array-no-value" => [
                "input" => ["key" => "foo", "value-not-there" => "bar"],
                "expected" => RuntimeException::class,
            ],
            "array" => [
                "input" => ["key" => "foo", "value" => "bar"],
                "expected" => ["foo" => "bar"],
            ],
            "array-deep" => [
                "input" => ["key" => "foo", "value" => ["bar" => "baz"]],
                "expected" => ["foo" => ["bar" => "baz"]],
            ],
        ];
        foreach ($tests as $name => $data) {
            $input = $data["input"];
            $expected = $data["expected"];
            $t = new ArrayToKeyValueTransformer("key", "value");
            $actual = null;
            try {
                $actual = $t->transform($input);
            } catch (Exception $e) {
                $actual = get_class($e);
            }
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
