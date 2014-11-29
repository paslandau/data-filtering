<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 01.09.14
 * Time: 13:49
 */

namespace paslandau\DataFiltering\Transformation;


use paslandau\WebUtility\WebUtil;

class UrlAbsolutizerTransformer extends AbstractBaseTransformer implements StringTransformerInterface
{

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string $baseUrl
     * @param StringTransformerInterface $predecessor
     * @param bool $dataCanBeNull
     */
    public function __construct($baseUrl, StringTransformerInterface $predecessor = null, $dataCanBeNull = null){
        $this->baseUrl = $baseUrl;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

	/**
     * Transforms an input url string in an absolute path by using $this->baseUrl
     *
     * @var string $data
     * @return string
     */
	protected function processData($data) {
        $res = WebUtil::relativeToAbsoluteUrl($data, $this->baseUrl);
        return $res;
    }

} 