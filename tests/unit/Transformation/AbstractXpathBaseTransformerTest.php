<?php

use paslandau\DataFiltering\Transformation\AbstractXpathBaseTransformer;
use paslandau\DomUtility\DomConverter;
use paslandau\DomUtility\DomConverterInterface;
use paslandau\IOUtility\IOUtil;
use paslandau\WebUtility\EncodingConversion\EncodingConverter;

class AbstractXpathBaseTransformerTestHelper extends AbstractXpathBaseTransformer
{
    public function publicProcessData($data)
    {
        return $this->processData($data);
    }
}

class AbstractXpathBaseTransformerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DOMXPath
     */
    private $xpath;

    public function setUp()
    {
        $path = __DIR__ . "/../resources/html5.html";
        $html = IOUtil::getFileContent($path);
        $enc = new EncodingConverter("utf-8", true, true);
        $converter = new DomConverter(DomConverterInterface::HTML, $enc);
        $doc = $converter->convert($html);
        $this->xpath = new DOMXPath($doc);
    }

    public function testMalformedXpath()
    {
        $this->setExpectedException(UnexpectedValueException::class);

        $xpath = "\\malformed";
        $t = new AbstractXpathBaseTransformerTestHelper($xpath);

        $result = @$this->xpath->query($xpath);
        $t->publicProcessData($result);
    }

    public function testWellformedXpath()
    {
        $xpath = "//h1";
        $t = new AbstractXpathBaseTransformerTestHelper($xpath);

        $result = @$this->xpath->query($xpath);
        $this->assertEquals(1,$result->length);
        $this->assertEquals("Straßenschäden",$result->item(0)->nodeValue);
    }
}
