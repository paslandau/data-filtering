<?php

use paslandau\DataFiltering\Exceptions\TransformationException;
use paslandau\DataFiltering\Transformation\RegexExistsTransformer;
use paslandau\IOUtility\IOUtil;

class RegexExistsTransformerTest extends PHPUnit_Framework_TestCase
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
            ["#<h1>(.*?)</h1>#u", null],
            ["#(<ul>)\\s*<li>(.*?)</li>#u", 2],
            ["#(<li>)(?P<name>.*?)</li>#", "name"]
        );
        foreach ($tests as $vals) {
            $expression = $vals[0];
            $groupIndex = $vals[1];
            $t = new RegexExistsTransformer($expression, $groupIndex);
            $actual = $t->transform($this->html);
            $msg = "$expression should be true";
            $this->assertTrue($actual, $msg);
        }
    }

    public function testFalse()
    {
        $tests = array(
            ["#<h2>(.*?)</h2>#u"],
            ["#(<ul>)\\s*<li style='foo'>(.*?)</li>#u"],
            ["#(<li>)Not Existing(?P<name>.*?)</li>#"]
        );
        foreach ($tests as $vals) {
            $expression = $vals[0];
            $t = new RegexExistsTransformer($expression);
            $actual = $t->transform($this->html);
            $msg = "$expression should be false";
            $this->assertFalse($actual, $msg);
        }
    }

    public function testUnexpectedGroup()
    {
        $this->setExpectedException(TransformationException::class);
        $expression = "#(<li>)(?P<name>.*?)</li>#";
        $groupIndex = 'wrongIndex';
        $t = new RegexExistsTransformer($expression, $groupIndex);
        $t->transform($this->html);

    }

    public function testMalformedRegex()
    {
        $this->setExpectedException(UnexpectedValueException::class);
        $t = new RegexExistsTransformer ("malformed");
        $t->transform($this->html);
    }

    public function testNullRegex()
    {
        $this->setExpectedException(UnexpectedValueException::class);
        $t = new RegexExistsTransformer (null);
        $t->transform($this->html);
    }
}
