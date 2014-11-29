<?php
namespace paslandau\DataFiltering\Transformation\Types;


interface HtmlFormInterface {

    /**
     * @return string|null
     */
    public function getAction();

    /**
     * @return string|null
     */
    public function getMethod();

    /**
     * @return string|null
     */
    public function getEnctype();
    /**
     * @return string|null
     */
    public function getAcceptCharset();

    /**
     * @return string[]
     */
    public function getFields();
}