<?php

use paslandau\DataFiltering\Transformation\StringReplaceTransformer;
use paslandau\DataFiltering\Transformation\UrlAbsolutizerTransformer;

class UrlAbsolutizerTransformerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The heavy lifting is done in WebUtil::relativeToAbsoluteUrl();
     */
    public function test_transform()
    {
        $tests = [
            "file" => [
                "input" => "foo.html",
                "baseUrl" => "http://www.example.com/",
                "expected" => "http://www.example.com/foo.html",
            ],
        ];
        foreach ($tests as $name => $data) {
            $input = $data["input"];
            $baseUrl =  $data["baseUrl"];
            $expected = $data["expected"];
            $t = new UrlAbsolutizerTransformer($baseUrl);
            $actual = null;
            try {
                $actual = $t->transform($input);
            } catch (Exception $e) {
                $actual = get_class($e);
            }
            $msg = [
                $name,
                "Input   : " . json_encode($input),
                "Expected: " . json_encode($expected),
                "Actual  : " . json_encode($actual),
            ];
            $msg = implode("\n", $msg);
            $this->assertEquals($expected, $actual, $msg);
        }
    }
}
