<?php

use paslandau\DataFiltering\Transformation\XpathTransformer;
use paslandau\DomUtility\DomConverter;
use paslandau\DomUtility\DomConverterInterface;
use paslandau\IOUtility\IOUtil;
use paslandau\WebUtility\EncodingConversion\EncodingConverter;

class XpathTransformerTest extends PHPUnit_Framework_TestCase
{

    private $htmlDoc;

    public function setUp()
    {
        $path = __DIR__ . "/../resources/html5.html";
        $html = IOUtil::getFileContent($path);
        $enc = new EncodingConverter("utf-8",true,true);
        $converter = new DomConverter(DomConverterInterface::HTML,$enc);
        $doc = $converter->convert($html);
        $this->htmlDoc = $doc;
    }

    public function testTrue()
    {
        $tests = array(
            "//h1" => [1, "Straßenschäden"],
            "(//li)[1]" => [1, "foo"],
            "//li" => [4, "foo\nbar\nbaz\n"]
        );
        foreach ($tests as $expression => $vals) {
            $count = $vals[0];
            $expected = $vals[1];
            $t = new XpathTransformer($expression);
            $actual = $t->transform($this->htmlDoc);
            $msg = "Expected Count: " . count($actual) . " == $count using $expression";
            $this->assertCount($count, $actual, $msg);
            $vals = array();
            foreach ($actual as $node) {
                $vals[] = $node->nodeValue;
            }
            $str = implode("\n", $vals);
            $msg = "Expected: $str == $expected using $expression";
            $this->assertEquals($expected, $str, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            "//h1" => [2, "wrong"],
            "(//li)[1]" => [3, "wrong"],
            "//li" => [5, "wrong\nwrong\nwrong\n"]
        );
        foreach ($tests as $expression => $vals) {
            $count = $vals[0];
            $expected = $vals[1];
            $t = new XpathTransformer($expression);
            $actual = $t->transform($this->htmlDoc);
            $msg = "Expected Count: " . count($actual) . " != $count using $expression";
            $this->assertNotCount($count, $actual, $msg);
            $vals = array();
            foreach ($actual as $node) {
                $vals[] = $node->nodeValue;
            }
            $str = implode("\n", $vals);
            $msg = "Expected: $str != $expected using $expression";
            $this->assertNotEquals($expected, $str, $msg);
        }
    }
}
