<?php

use paslandau\DataFiltering\Exceptions\TransformationException;
use paslandau\DataFiltering\Transformation\RegexTransformer;
use paslandau\IOUtility\IOUtil;

class RegexTransformerTest extends PHPUnit_Framework_TestCase
{

    private $html;

    public function setUp()
    {
        $path = __DIR__ . "/../resources/html5.html";
        $this->html = IOUtil::getFileContent($path);
    }

    public function testTrue()
    {
        $tests = array(
            ["#<h1>(.*?)</h1>#u", 1, 1, "Straßenschäden"],
            ["#(<ul>)\\s*<li>(.*?)</li>#u", 1, 2, "foo"],
            ["#(<li>)(?P<name>.*?)</li>#", 4, 'name', "foo\nbar\nbaz\n"]
        );
        foreach ($tests as $vals) {
            $expression = $vals[0];
            $count = $vals[1];
            $groupIndex = $vals[2];
            $expected = $vals[3];
            $t = new RegexTransformer($expression, $groupIndex);
            $actual = $t->transform($this->html);
            $msg = "Expected Count: " . count($actual) . " == $count using $expression";
            $this->assertCount($count, $actual, $msg);
            $str = implode("\n", $actual);
            $msg = "Expected: $str == $expected using $expression";
            $this->assertEquals($expected, $str, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            ["#<h1>(.*?)</h1>#u", 2, 1, "wrong"],
            ["#(<ul>)\\s*<li>(.*?)</li>#u", 3, 2, "bar"],
            ["#(<li>)(?P<name>.*?)</li>#", 5, 'name', "wrong"]
        );
        foreach ($tests as $vals) {
            $expression = $vals[0];
            $count = $vals[1];
            $groupIndex = $vals[2];
            $expected = $vals[3];
            $t = new RegexTransformer($expression, $groupIndex);
            $actual = $t->transform($this->html);
            $msg = "Expected Count: " . count($actual) . " != $count using $expression";
            $this->assertNotCount($count, $actual, $msg);
            $str = implode("\n", $actual);
            $msg = "Expected: $str != $expected using $expression";
            $this->assertNotEquals($expected, $str, $msg);
        }
    }

    public function testUnexpectedGroup()
    {
        $this->setExpectedException(TransformationException::class);
        $expression = "#(<li>)(?P<name>.*?)</li>#";
        $groupIndex = 'wrongIndex';
        $t = new RegexTransformer($expression, $groupIndex);
        $t->transform($this->html);

    }

    public function testMalformedRegex()
    {
        $this->setExpectedException(UnexpectedValueException::class);
        $t = new RegexTransformer ("malformed");
        $t->transform($this->html);
    }

    public function testNullRegex()
    {
        $this->setExpectedException(UnexpectedValueException::class);
        $t = new RegexTransformer (null);
        $t->transform($this->html);
    }

}
