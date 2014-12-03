<?php

use paslandau\ArrayUtility\ArrayPath\ArrayMergingStrategy;
use paslandau\ArrayUtility\ArrayPath\ArrayMergingStrategyInterface;
use paslandau\ArrayUtility\ArrayPath\ArrayPath;
use paslandau\ArrayUtility\ArrayPath\ArraySelector;
use paslandau\DataFiltering\Transformation\ArrayFlipTransformer;
use paslandau\DataFiltering\Transformation\ArrayMergeTransformer;
use paslandau\DataFiltering\Transformation\ArrayMergingDescriptor;

class ArrayMergingDescriptorTest extends PHPUnit_Framework_TestCase
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
                "target" => [
                    "foo" => [
                        "baz",
                    ]
                ],
                "pathSource" => '["foo"]',
                "pathTarget" => '["foo"]',
                "strategy" => new ArrayMergingStrategy(ArrayMergingStrategyInterface::STRATEGY_MERGE),
                "expected" => ["foo" => ["baz", "bar"]],
            ],
            "array-deep" => [
                "input" => [
                    "foo" => [
                        "foo2" => [
                            "bar" => "bar"
                        ],
                    ]
                ],
                "target" => [
                    "foo" => [
                        "baz",
                    ]
                ],
                "pathSource" => '["foo"]["foo2"]',
                "pathTarget" => '["foo"]',
                "strategy" => new ArrayMergingStrategy(ArrayMergingStrategyInterface::STRATEGY_MERGE),
                "expected" => ["foo" => ["baz", "bar" => "bar"]],
            ],
        ];
        foreach ($tests as $name => $data) {
            $input = new ArraySelector($data["input"]);
            $target = new ArraySelector($data["target"]);
            $pathSource = new ArrayPath($data["pathSource"]);
            $pathTarget = new ArrayPath($data["pathTarget"]);
            $strategy = $data["strategy"];
            $expected = $data["expected"];
            $descriptor = new ArrayMergingDescriptor($pathSource,$pathTarget,$strategy);
            $actual = $target;
            $descriptor->merge($input,$actual);
            $msg = [
                $name,
                "Input     : " . json_encode($input->getArray()),
                "InputPath : " . json_encode($pathSource->__toString()),
                "Target    : " . json_encode($target->getArray()),
                "TargetPath: " . json_encode($pathTarget->__toString()),
                "Expected  : " . json_encode($expected),
                "Actual    : " . json_encode($actual->getArray()),
            ];
            $msg = implode("\n", $msg);
            $this->assertEquals($expected, $actual->getArray(), $msg);
        }
    }
}
