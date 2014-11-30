<?php

namespace paslandau\DataFiltering\Transformation;

class StringReplaceTransformer extends AbstractBaseTransformer implements StringTransformerInterface
{

    /**
     * @var string[]
     */
    private $replacements;

    /**
     * @param string[] $replacements
     * @param StringTransformerInterface $predecessor
     * @param boolean $dataCanBeNull
     * @internal param string $baseUrl
     */
    public function __construct(array $replacements, StringTransformerInterface $predecessor = null, $dataCanBeNull = null)
    {
        $this->replacements = $replacements;
        parent::__construct($predecessor, $dataCanBeNull);
    }

    /**
     * @return \string[]
     */
    public function getReplacements()
    {
        return $this->replacements;
    }

    /**
     * @param \string[] $replacements
     */
    public function setReplacements($replacements)
    {
        $this->replacements = $replacements;
    }

    /**
     * Uses str_replace with $this->replacements on $data
     * @var string $data
     * @return string
     */
    protected function processData($data)
    {
        $res = str_replace(array_keys($this->replacements), array_values($this->replacements), $data);
        return $res;
    }

} 