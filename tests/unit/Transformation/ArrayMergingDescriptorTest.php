<?php

use paslandau\ArrayUtility\ArrayPath\ArrayMergingStrategy;
use paslandau\ArrayUtility\ArrayPath\ArrayMergingStrategyInterface;
use paslandau\ArrayUtility\ArrayPath\ArrayPath;
use paslandau\DataFiltering\Transformation\ArrayFlipTransformer;
use paslandau\DataFiltering\Transformation\ArrayMergeTransformer;
use paslandau\DataFiltering\Transformation\ArrayMergingDescriptor;

class ArrayMergingDescriptorTest extends PHPUnit_Framework_TestCase
{
    public function test_transform()
    {
//        $tests = [
//            "array" => [
//                "input" => [
//                    "foo" => [
//                        "bar",
//                    ]
//                ],
//                "target" => [
//                    "foo" => [
//                        "bar",
//                    ]
//                ],
//                "pathSource" => "",
//                "pathTarget" => "",
//                "strategy" => "",
//                "expected" => ["bar"],
//            ],
//        ];
//        foreach ($tests as $name => $data) {
//            $input = $data["input"];
//            $target = $data["target"];
//            $pathSource = $data["pathSource"];
//            $pathTarget = $data["pathTarget"];
//            $strategy = $data["strategy"];
//            $expected = $data["expected"];
//            $descriptor = new ArrayMergingDescriptor($pathSource,$pathTarget,$strategy);
//            //TODO WRITE TEST!!!
//            $actual = $descriptor->merge($input,$target);
//            $msg = [
//                $name,
//                "Input   : " . json_encode($input),
//                "Expected: " . json_encode($expected),
//                "Actual  : " . json_encode($actual),
//            ];
//            $msg = implode("\n", $msg);
//            $this->assertEquals($expected, $actual, $msg);
//        }
    }
}
