<?php

use paslandau\DataFiltering\Transformation\StringReplaceTransformer;

class StringReplaceTransformerTest extends PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
        $tests = [
            "empty" => [
                "input" => "",
                "expected" => "",
            ],
            "array-no-key" => [
                "input" => "Nothing to replace",
                "expected" => "Nothing to replace"
            ],
            "replacements" => [
                "input" => "[1] wird zu [2]",
                "expected" => "foo wird zu bar",
            ],
            "multiple-same-replacements" => [
                "input" => "[1] [1] wird zu [2]",
                "expected" => "foo foo wird zu bar",
            ],
        ];
        foreach ($tests as $name => $data) {
            $input = $data["input"];
            $expected = $data["expected"];
            $replacements = [
                "[1]" => "foo",
                "[2]" => "bar"
            ];
            $t = new StringReplaceTransformer($replacements);
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
