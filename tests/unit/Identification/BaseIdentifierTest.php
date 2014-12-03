<?php

use paslandau\ComparisonUtility\ComparisonObjectInterface;
use paslandau\DataFiltering\Evaluation\DataEvaluator;
use paslandau\DataFiltering\Evaluation\DataEvaluatorInterface;
use paslandau\DataFiltering\Events\DataEmitterInterface;
use paslandau\DataFiltering\Events\DataProcessedEvent;
use paslandau\DataFiltering\Extraction\DataExtractorInterface;
use paslandau\DataFiltering\Identification\BaseIdentifier;

class BaseIdentifierTest extends PHPUnit_Framework_TestCase
{
    public function test_isIdentifiedBy()
    {
        $mock = $this->getMock(DataEvaluatorInterface::class);
        $mock->expects($this->once())->method("solve")->will($this->returnArgument(0));
        /** @var DataEvaluatorInterface $mock */

        $e = new BaseIdentifier($mock, "base");

        $input = true;
        $expected = $input;
        $actual = $e->isIdentifiedBy($input);
        $this->assertEquals($expected, $actual, "Wrong value returned");
    }

    public function test_isIdentifiedBy_hierarchical(){

        $parentId = "parent";
        $childId = "child";

        $tests = [
            "none" => [
                "input" => ["foo", "bar"],
                "expected" => false
            ],
            "only-parent" => [
                "input" => ["parent"],
                "expected" => false
            ],
            "only-child" => [
                "input" => ["child"],
                "expected" => false
            ],
            "parent-child" => [
                "input" => ["parent","child"],
                "expected" => true
            ],
        ];

        $mock = $this->getMock(DataEvaluatorInterface::class);
        $f = function($data) use ($parentId){
          return in_array($parentId,$data);
        };
        $mock->expects($this->exactly(count($tests)))->method("solve")->will($this->returnCallback($f));
        /** @var DataEvaluatorInterface $mock */
        $parent = new BaseIdentifier($mock, "parent");

        $mockChild = $this->getMock(DataEvaluatorInterface::class);
        $f = function($data) use ($childId){
            return in_array($childId,$data);
        };
        // "solve" will only be called if parent was successfully identified
        $count = count($tests) - 2;
        $mockChild->expects($this->exactly($count))->method("solve")->will($this->returnCallback($f));
        /** @var DataEvaluatorInterface $mockChild */
        $child = new BaseIdentifier($mockChild, "child", $parent);

        foreach($tests as $test => $data){
            $input = $data["input"];
            $expected = $data["expected"];
            $actual = $child->isIdentifiedBy($input);
            $this->assertEquals($expected, $actual, "Test '$test' failed. Wrong value returned");
        }
    }

    public function test_getParentIdentifiers()
    {
        $mock = $this->getMock(DataEvaluatorInterface::class);
        /** @var DataEvaluatorInterface $mock */
        $greatParent = new BaseIdentifier($mock, "greatParent");
        $parent = new BaseIdentifier($mock, "parent", $greatParent);
        $child = new BaseIdentifier($mock, "child", $parent);

        $expected = ["parent","greatParent"];
        $parents = $child->getParentIdentifiers();
        $this->assertSame($expected, $parents, "Wrong value returned");

        $expected = [];
        $child->setParent(null);
        $parents = $child->getParentIdentifiers();
        $this->assertSame($expected, $parents, "Wrong value returned");
    }
}
