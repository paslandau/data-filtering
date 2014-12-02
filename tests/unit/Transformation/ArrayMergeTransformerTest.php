<?php

use paslandau\ArrayUtility\ArrayPath\ArrayMergingStrategy;
use paslandau\ArrayUtility\ArrayPath\ArrayMergingStrategyInterface;
use paslandau\ArrayUtility\ArrayPath\ArrayPath;
use paslandau\DataFiltering\Transformation\ArrayFlipTransformer;
use paslandau\DataFiltering\Transformation\ArrayMergeTransformer;
use paslandau\DataFiltering\Transformation\ArrayMergingDescriptor;

class ArrayMergeTransformerTest extends PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
        $tests = [
            "array" => [
                "input" => [
                    "foo" => [
                        "bar",
                    ]
                ],
                "descriptor" => new ArrayMergingDescriptor(
                    new ArrayPath('["foo"]'),
                    new ArrayPath(''),
                    new ArrayMergingStrategy(ArrayMergingStrategyInterface::STRATEGY_MERGE)),
                "expected" => ["bar"],
            ],
            "deep-array" => [
                "input" => [
                    "foo" => [
                        "foo2" => [
                            "baz"
                        ]
                    ],

                ],
                "descriptor" => new ArrayMergingDescriptor(
                    new ArrayPath('["foo"]'),
                    new ArrayPath(''),
                    new ArrayMergingStrategy(ArrayMergingStrategyInterface::STRATEGY_MERGE)),
                "expected" => ["foo2" => ["baz"]],
            ],
        ];
        foreach ($tests as $name => $data) {
            $input = $data["input"];
            $descriptor = $data["descriptor"];
            $expected = $data["expected"];
            $t = new ArrayMergeTransformer([$descriptor]);
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
