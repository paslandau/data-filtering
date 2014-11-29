<?php

use paslandau\DataFiltering\Transformation\XpathExistsTransformer;
use paslandau\IOUtility\IOUtil;

class XpathExistsTransformerTest extends PHPUnit_Framework_TestCase
{

    private $htmlDoc;

    public function setUp()
    {
        $path = __DIR__ . "/../resources/html5.html";
        $html = IOUtil::getFileContent($path);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $this->htmlDoc = $doc;
    }

    public function testTrue()
    {
        $tests = array(
            ["//h1"],
            ["(//li)[1]"],
            ["//li"],

        );
        foreach ($tests as $vals) {
            $expression = $vals[0];
            $input = $this->htmlDoc;
            $t = new XpathExistsTransformer($expression);
            $actual = $t->transform($input);
            $msg = "$expression should be true";
            $this->assertTrue($actual, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            ["//h2"],
            ["(//li)[6]"],
            ["//ol"],

        );
        foreach ($tests as $vals) {
            $expression = $vals[0];
            $input = $this->htmlDoc;
            $t = new XpathExistsTransformer($expression);
            $actual = $t->transform($input);
            $msg = "$expression should be false";
            $this->assertfalse($actual, $msg);
        }
    }

    public function testMalformed()
    {
        $this->setExpectedException(get_class(new UnexpectedValueException ()));
        $t = new XpathExistsTransformer ("\\malformed");
        $t->transform($this->htmlDoc);
    }

    public function testNull()
    {
        $this->setExpectedException(get_class(new UnexpectedValueException ()));
        $t = new XpathExistsTransformer (null);
        $t->transform($this->htmlDoc);
    }

}
