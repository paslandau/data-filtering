<?php

namespace paslandau\DataFiltering\Transformation\Types;


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
     * @param string|null $enctype [optional]. Default: null.
     * @param string|null $acceptCharset [optional]. Default: null.
     */
    function __construct($method = null, $action = null, array $fields = null, $enctype = null, $acceptCharset = null)
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

} 