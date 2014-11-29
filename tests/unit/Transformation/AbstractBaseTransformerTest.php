<?php

use paslandau\DataFiltering\Transformation\AbstractBaseTransformer;

class BaseTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testNullTrue()
    {
        $expected = null;
        $t = $this->getMockForAbstractClass('paslandau\DataFiltering\Transformation\AbstractBaseTransformer',[null, true]);

        $actual = $t->Transform($expected);
        $msg = "Expected: $actual == $expected";
        $this->assertEquals($expected, $actual, $msg);
    }

    public function testNullFalse()
    {
        $this->setExpectedException(get_class(new UnexpectedValueException()));
        $expected = null;
        $t = $this->getMockForAbstractClass('paslandau\DataFiltering\Transformation\AbstractBaseTransformer',[null, false]);
        $t->Transform($expected);
    }

    public function testRecursion()
    {
        $mock = $this->getMock('paslandau\DataFiltering\Transformation\DataTransformerInterface');
        $callback = function ($s) {
            return strtoupper($s);
        };
        $mock->expects($this->any())->method("Transform")->will($this->returnCallback($callback));
        $t = $this->getMockForAbstractClass('paslandau\DataFiltering\Transformation\AbstractBaseTransformer',[$mock]);

        $input = "hallo";
        $expected = "HALLO";
        $actual = $t->Transform($input);
        $msg = "Expected: $actual == $expected";
        $this->assertEquals($expected, $actual, $msg);
    }

    public function testEnabledCache(){
        $calls = 5;

        $mock = $this->getMock('paslandau\DataFiltering\Transformation\DataTransformerInterface');
        $callback = function ($s) {
            return strtoupper($s);
        };
        $mock->expects($this->once())->method("transform")->will($this->returnCallback($callback));
        /** @var AbstractBaseTransformer $t */
        $t = $this->getMockForAbstractClass('paslandau\DataFiltering\Transformation\AbstractBaseTransformer',[$mock],'', TRUE, TRUE, TRUE, array('processData'));
        $t->expects($this->once())->method("processData")->will($this->returnArgument(0));
        $t->setIsCacheActive(true);

        $input = "hallo";
        $expected = "HALLO";
        for($i = 0; $i < $calls; $i++){
            $actual = $t->transform($input);
        }
        $msg = "Expected: $actual == $expected";
        $this->assertEquals($expected, $actual, $msg);
    }

    public function testDisabledCache(){
        $calls = 5;

        $mock = $this->getMock('paslandau\DataFiltering\Transformation\DataTransformerInterface');
        $callback = function ($s) {
            return strtoupper($s);
        };
        $mock->expects($this->exactly(5))->method("transform")->will($this->returnCallback($callback));
        /** @var AbstractBaseTransformer $t */
        $t = $this->getMockForAbstractClass('paslandau\DataFiltering\Transformation\AbstractBaseTransformer',[$mock],'', TRUE, TRUE, TRUE, array('processData'));
        $t->expects($this->exactly(5))->method("processData")->will($this->returnArgument(0));
        $t->setIsCacheActive(false);

        $input = "hallo";
        $expected = "HALLO";
        for($i = 0; $i < $calls; $i++){
            $actual = $t->transform($input);
        }
        $msg = "Expected: $actual == $expected";
        $this->assertEquals($expected, $actual, $msg);
    }
}
