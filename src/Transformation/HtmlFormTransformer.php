<?php
namespace paslandau\DataFiltering\Transformation;

use paslandau\DataFiltering\Transformation\Types\HtmlForm;
use paslandau\DataFiltering\Transformation\Types\HtmlFormInterface;

class HtmlFormTransformer extends AbstractBaseTransformer implements HtmlFormTransformerInterface
{
    /**
     * @param DataTransformerInterface $predecessor [optional]. Default: null.
     * @param null $dataCanBeNull [optional]. Default: null.
     */
    public function __construct(DataTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @var \DOMNode $data
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @return HtmlFormInterface
     */
    protected function processData(\DOMNode $data)
    {
        $formNode = $data;
        $xpath = new \DOMXPath($formNode->ownerDocument);
        $attributes = array(
            "action" => "./@action",
            "method" => "./@method",
            "enctype" => "./@enctype",
            "acceptCharset" => "./@accept-charset"
        );
        $htmlForm = array();
        foreach ($attributes as $prop => $query) {
            $htmlForm[$prop] = null;
            $nodes = $xpath->query($query, $formNode);
            if ($nodes->length > 0) {
                $htmlForm[$prop] = $nodes->item(0)->nodeValue;
            }
        }
        $elements = array(
            ".//input[not(@type='checkbox') and not(@type='radio') and not(@type='submit') and not(@type='reset') and @name]" => array("./@value"),
            ".//input[(@type='checkbox' or @type='radio') and @checked and @name]" => array("./@value"),
            ".//textarea[@name]" => array("./text()"),
            ".//select[@name]" => array(".//option[@selected]/@value")
        );
        $allParams = array();
        foreach ($elements as $elExp => $attrExp) {
            $nodes = $xpath->query($elExp, $formNode);
            foreach ($nodes as $node) {
                $name = $this->GetXpath($xpath, $node, "./@name");
                $valueXpath = $attrExp[0];
                $value = $this->GetXpath($xpath, $node, $valueXpath);
                if ($value === null) {
                    $value = "";
                }

                $str = "$name=" . urlencode($value);
                $allParams[] = $str;
            }
        }
        $params = null;
        if (count($allParams) > 0) {
            $allParamsString = implode("&", $allParams);
            parse_str($allParamsString, $params);
        }
        $res = new HtmlForm($htmlForm["method"], $htmlForm["action"], $params, $htmlForm["enctype"], $htmlForm["acceptCharset"]);
        return $res;
    }

    private function GetXpath(\DOMXPath $xpath, \DOMNode $node, $expression)
    {
        $innerNodes = $xpath->query($expression, $node);
        if ($innerNodes->length > 0) {
            return $innerNodes->item(0)->nodeValue;
        }
        return null;
    }

}
