<?php

use paslandau\DataFiltering\Transformation\ArrayToKeyValueTransformer;
use paslandau\DataFiltering\Transformation\Types\HtmlForm;

class HtmlFormTest extends PHPUnit_Framework_TestCase
{

    private function getDomNode($formHtml){
        $content = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\"><html><head><meta http-equiv='content-type' content='text/html; charset=utf-8'><title>HTML form</title></head><body>$formHtml</body></html>";
        $doc = new DOMDocument(1,"utf-8");
        $doc->loadHTML($content);
        $xpath = new DOMXPath($doc);
        $node = $xpath->query("//form[@id='form']")->item(0);
        return $node;
    }

    public function test_fromDomNode()
    {
        $tests = [
            "form" => [
                "input" => "<form id='form' method='GET' action='/foo.html' enctype='application/x-www-form-urlencoded' accept-charset='utf-8'>
</form>",
                "expected" => new HtmlForm("GET","/foo.html",[],'application/x-www-form-urlencoded','utf-8'),
            ],
            "form-all-fields" => [
                "input" => "<form id='form' method='GET' action='/foo.html' enctype='application/x-www-form-urlencoded' accept-charset='utf-8'>
    <input type=\"text\" name=\"input-text\" value=\"input-text-value\" />
    <input type=\"email\" name=\"input-email\" value=\"input-email-value\" />
    <input type=\"password\" name=\"input-password\" value=\"input-password-value\" />
    <input type=\"hidden\" name=\"input-hidden\" value=\"input-hidden-value\" />
    <input type=\"checkbox\" name=\"input-checkbox\" value=\"input-checkbox-value\" checked=\"checked\"/>
    <input type=\"radio\" name=\"input-radio\" value=\"input-radio-value\" checked=\"checked\"/>
    <textarea name=\"textarea\">textarea-value</textarea>
    <select name=\"select\">
        <option name=\"option\" value=\"option-value\" selected=\"selected\"></option>
    </select>
    <input type=\"submit\" name=\"input-submit\" value=\"input-submit-value\" />
    <input type=\"reset\" name=\"input-reset\" value=\"input-reset-value\" />
    <input type=\"button\" name=\"input-button\" value=\"input-button-value\" />
</form>",
                "expected" => new HtmlForm("GET","/foo.html",[
                    "input-text" => "input-text-value",
                    "input-email" => "input-email-value",
                    "input-password" => "input-password-value",
                    "input-radio" => "input-radio-value",
                    "input-checkbox" => "input-checkbox-value",
                    "textarea" => "textarea-value",
                    'select' => 'option-value',
                    'input-hidden' => 'input-hidden-value'
                ],'application/x-www-form-urlencoded','utf-8'),
            ],
            "overriding-values" => [
                "input" => "<form id='form' method='GET' action='/foo.html' enctype='application/x-www-form-urlencoded' accept-charset='utf-8'>
    <input type=\"text\" name=\"input-text\" value=\"input-text-value1\" />
    <input type=\"text\" name=\"input-text\" value=\"input-text-value2\" />
</form>",
                "expected" => new HtmlForm("GET","/foo.html",[
                    "input-text" => "input-text-value2"
                ],'application/x-www-form-urlencoded','utf-8'),
            ],
            "array-values" => [
                "input" => "<form id='form' method='GET' action='/foo.html'>
    <input type=\"text\" name=\"input-text[]\" value=\"input-text-value1\" />
    <input type=\"text\" name=\"input-text[]\" value=\"input-text-value2\" />
</form>",
                "expected" => new HtmlForm("GET","/foo.html",[
                    "input-text" => ["input-text-value1", "input-text-value2"]
                ])
            ],
            "array-values-named-keys" => [
                "input" => "<form id='form' method='GET' action='/foo.html'>
    <input type=\"text\" name=\"input-text[foo]\" value=\"input-text-value1\" />
    <input type=\"text\" name=\"input-text[bar]\" value=\"input-text-value2\" />
</form>",
                "expected" => new HtmlForm("GET","/foo.html",[
                    "input-text" => ["foo" => "input-text-value1", "bar" => "input-text-value2"]
                ])
            ],
            "array-values-named-keys-deep" => [
                "input" => "<form id='form' method='GET' action='/foo.html'>
    <input type=\"text\" name=\"input-text[foo][baz]\" value=\"input-text-value1\" />
    <input type=\"text\" name=\"input-text[bar]\" value=\"input-text-value2\" />
</form>",
                "expected" => new HtmlForm("GET","/foo.html",[
                    "input-text" => ["foo" => ["baz" => "input-text-value1"], "bar" => "input-text-value2"]
                ])
            ],
            "dot-space-in-key" => [
                "input" => "<form id='form' method='GET' action='/foo.html'>
    <input type=\"text\" name=\"dot.key\" value=\"input-text-value1\" />
    <input type=\"text\" name=\"space key\" value=\"input-text-value2\" />
</form>",
                "expected" => new HtmlForm("GET","/foo.html",[
                    "dot.key" => "input-text-value1",
                    "space key" => "input-text-value2"
                ])
            ],
        ];
        foreach ($tests as $name => $data) {
            $input = $this->getDomNode($data["input"]);
            $expected = $data["expected"];
            $actual = null;
            try {
                $actual = HtmlForm::fromDomNode($input);
            } catch (Exception $e) {
                $actual = get_class($e);
            }
            $msg = [
                $name,
                "Input   : " . json_encode($data["input"]),
                "Expected: " . json_encode($expected->__toString()),
                "Actual  : " . json_encode($actual->__toString()),
            ];
            $msg = implode("\n", $msg);
            $this->assertEquals($expected, $actual, $msg);
        }
    }
}
