<?php

namespace paslandau\DataFiltering\Transformation\Types;


use paslandau\ArrayUtility\ArrayUtil;

class HtmlForm implements HtmlFormInterface{

    /**
     * @var string|null
     */
    private $action;

    /**
     * @var string|null
     */
    private $method;

    /**
     * @var string|null
     */
    private $enctype;

    /**
     * @var string|null
     */
    private $acceptCharset;

    /**
     * @var string[]
     */
    private $fields;

    /**
     * @param string|null $method [optional]. Default: null.
     * @param string|null $action [optional]. Default: null.
     * @param string[]|null $fields [optional]. Default: null (array()).
     * @param string|null $enctype [optional]. Default: null. Possible values: "application/x-www-form-urlencoded", "multipart/form-data", "text/plain" @see http://www.w3schools.com/tags/att_form_enctype.asp
     * @param string|null $acceptCharset [optional]. Default: null.
     */
    public function __construct($method = null, $action = null, array $fields = null, $enctype = null, $acceptCharset = null)
    {
        $this->acceptCharset = $acceptCharset;
        $this->action = $action;
        $this->enctype = $enctype;
        if($fields === null){
            $fields = [];
        }
        $this->fields = $fields;
        $this->method = $method;
    }

    public static function fromDomNode(\DOMNode $formNode){
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
        //Use whiteliste for input-element instead of blacklist?
        // ==> no. Blacklist is more forgiving and will probably right for the majority of cases
        $elements = array(
            ".//input[not(@type='checkbox') and not(@type='radio') and not(@type='submit') and not(@type='reset') and not(@type='button') and @name]" => array("./@value"),
            ".//input[(@type='checkbox' or @type='radio') and @checked and @name]" => array("./@value"),
            ".//textarea[@name]" => array("./text()"),
            ".//select[@name]" => array(".//option[@selected]/@value")
        );
        $allParams = array();
        foreach ($elements as $elExp => $attrExp) {
            $nodes = $xpath->query($elExp, $formNode);
            foreach ($nodes as $node) {
                // we made sure @name exists in the outer expression
                $name = self::getXpath($xpath, $node, "./@name");
                $valueXpath = $attrExp[0];
                $value = self::getXpath($xpath, $node, $valueXpath);
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
            $params = self::parseStr($allParamsString);
        }

        return new self($htmlForm["method"], $htmlForm["action"], $params, $htmlForm["enctype"], $htmlForm["acceptCharset"]);
    }

    /**
     * Replacement for PHP's parse_string which does not deal with spaces or dots in key names
     *
     * @param string $string URL query string
     * @return array key value pairs
     */
    private static function parseStr( $string ) {
        $chars = [
            "." => ".",
            " " => " ",
        ];

        // check if we even need to handle special chars
        $found = false;
        foreach($chars as $char => $v){
            if(mb_strpos($string,$char)){
                $found = true;
                break;
            }
        }
        if(!$found){
            // if not - return
            parse_str($string,$params);
            return $params;
        }

        //otherwise, let the crazyness begin

        //build uid
        $patterns = [];
        foreach($chars as $key => $v) {
            $rand = "";
            do {
                $rand .= rand(0,1000000);
            } while (mb_strpos($string,$rand) !== false);
            $patterns[$key] = $rand;
        }
        //sort by length descending to avoid overlap
        uasort($patterns,function($s1,$s2){
            return mb_strlen($s1) > mb_strlen($s2) ? -1 : 1;
        });

        // replace chars
        $replacements = [];
        foreach($patterns as $char => $escape){
            $string = preg_replace("#".preg_quote($char)."#u",$escape,$string,-1,$count);
            if($count > 0){
                $replacements[$escape] = $chars[$char];
            }
        }

        parse_str($string,$params);

        // reverse replacement
        if(count($replacements) > 0){
            $replaceBack = function($str) use($replacements){
                foreach($replacements as $escape => $char){
                    $str = preg_replace("#".preg_quote($escape)."#u",$char,$str);
                }
                return $str;
            };

            $replaceBackRec = function(array $arr) use(&$replaceBackRec, $replaceBack){
                foreach($arr as $key => $val){
                    $newkey = $replaceBack($key);
                    if(is_array($val)){
                        $val = $replaceBackRec($val);
                    }else{
                        $val = $replaceBack($val);
                    }
                    unset($arr[$key]);
                    $arr[$newkey] = $val;
                }
                return $arr;
            };

            $params = $replaceBackRec($params);
        }

        return $params;
    }

    private static function getXpath(\DOMXPath $xpath, \DOMNode $node, $expression)
    {
        $innerNodes = $xpath->query($expression, $node);
        if ($innerNodes->length > 0) {
            return $innerNodes->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * @param null|string $acceptCharset
     */
    public function setAcceptCharset($acceptCharset = null)
    {
        $this->acceptCharset = $acceptCharset;
    }

    /**
     * @return null|string
     */
    public function getAcceptCharset()
    {
        return $this->acceptCharset;
    }

    /**
     * @param null|string $action
     */
    public function setAction($action = null)
    {
        $this->action = $action;
    }

    /**
     * @return null|string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param null|string $enctype
     */
    public function setEnctype($enctype = null)
    {
        $this->enctype = $enctype;
    }

    /**
     * @return null|string
     */
    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * @param string[] $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return string[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param null|string $method
     */
    public function setMethod($method = null)
    {
        $this->method = $method;
    }

    /**
     * @return null|string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param HtmlForm $form
     * @return bool
     */
    public function equals(HtmlForm $form){
        foreach($this as $key => $value){
            $method = "get".$key;
            $compare = $form->{$method}();
            if($value != $compare){
                return false;
            }
        }
        return true;
    }

    public function __toString(){
        $arr = get_object_vars($this);
        return ArrayUtil::toString($arr);
    }

} 