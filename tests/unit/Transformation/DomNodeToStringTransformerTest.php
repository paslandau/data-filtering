<?php

use paslandau\DataFiltering\Transformation\DomNodeToStringTransformer;
use paslandau\IOUtility\IOUtil;

class DomNodeToStringTransformerTest extends PHPUnit_Framework_TestCase
{

    private $domNode;

    public function setUp()
    {
        $path = __DIR__ . "/../resources/html5.html";
        $html = IOUtil::getFileContent($path);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        $domNode = $xpath->query("//header")->item(0);
        $this->domNode = $domNode;
    }

    public function testTrue()
    {
        $tests = array(
            DomNodeToStringTransformer::METHOD_NODE_VALUE => "Straßenschäden",
            DomNodeToStringTransformer::METHOD_INNER_HTML => "<h1>Straßenschäden</h1>",
            DomNodeToStringTransformer::METHOD_OUTER_HTML => "<header><h1>Straßenschäden</h1></header>",
        );
        foreach ($tests as $method => $expected) {
            $t = new DomNodeToStringTransformer($method);
            $actual = $t->transform($this->domNode);
            $msg = "Expected: $actual == $expected using $method";
            $this->assertEquals($expected, $actual, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            DomNodeToStringTransformer::METHOD_NODE_VALUE => "wrong",
            DomNodeToStringTransformer::METHOD_INNER_HTML => "wrong",
            DomNodeToStringTransformer::METHOD_OUTER_HTML => "<h1>wrong</h1>",
        );
        foreach ($tests as $method => $expected) {
            $t = new DomNodeToStringTransformer($method);
            $actual = $t->transform($this->domNode);
            $msg = "Expected: $actual != $expected using $method";
            $this->assertNotEquals($expected, $actual, $msg);
        }
    }

    public function testUnknownMethod()
    {
        $this->setExpectedException(get_class(new InvalidArgumentException()));
        $tests = array(
            "malformed",
            null
        );
        foreach ($tests as $method) {
            new DomNodeToStringTransformer ($method);
        }
    }
}
