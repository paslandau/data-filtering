<?php

use paslandau\DataFiltering\Events\DataEmitterInterface;
use paslandau\DataFiltering\Events\DataProcessedEvent;
use paslandau\DataFiltering\Extraction\AbstractBaseExtractor;
use paslandau\DataFiltering\Extraction\InputDataExtractor;
use paslandau\DataFiltering\Extraction\MultiDataExtractor;
use paslandau\DataFiltering\Transformation\ArrayFlipTransformer;
use paslandau\DataFiltering\Transformation\DataTransformerInterface;

class MultiDataExtractorTest extends PHPUnit_Framework_TestCase
{
    public function test_extract()
    {
        $extractors = [
            "foo" => new InputDataExtractor("fooVal"),
            "bar" => new InputDataExtractor("barVal"),
        ];
        $e = new MultiDataExtractor($extractors);

        $input = "does-not-matter";
        $expected = ["foo" => "fooVal", "bar" => "barVal"];
        $actual = $e->getData($input);

        $this->assertSame($expected,$actual,"Wrong value extracted");
    }

    public function test_addAndRemoveExtractors(){
        $extractors = [
            "foo" => new InputDataExtractor("fooVal"),
            "bar" => new InputDataExtractor("barVal"),
        ];
        $e = new MultiDataExtractor($extractors);

        $this->assertSame($extractors,$e->getExtractors(),"Getting extractors after constructor failed");

        $e->removeExtractor($extractors["bar"]);
        $check = $extractors;
        unset($check["bar"]);
        $this->assertSame($check,$e->getExtractors(),"Removal by extractor failed, only 'foo' should be left");

        $baz = new InputDataExtractor("bazVal");
        $e->addExtractor("baz", $baz);
        $check["baz"] = $baz;
        $this->assertSame($check,$e->getExtractors(),"Adding failed, expected 'foo' and 'baz'");

        $baz2 = new InputDataExtractor("baz2Val");
        $e->addExtractor("baz", $baz2);
        $check["baz"] = $baz2;
        $this->assertSame($check,$e->getExtractors(),"Overwriting 'baz' failed, expected 'foo' and 'baz2'");

        $e->removeExtractor("foo");
        unset($check["foo"]);
        $this->assertSame($check,$e->getExtractors(),"Removal by string failed, only 'baz2' should be left");

    }
}
