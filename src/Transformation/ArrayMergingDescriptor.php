<?php

namespace paslandau\DataFiltering\Transformation;


use paslandau\ArrayUtility\ArrayPath\ArrayMergingStrategyInterface;
use paslandau\ArrayUtility\ArrayPath\ArrayPathInterface;
use paslandau\ArrayUtility\ArrayPath\ArraySelectorInterface;

class ArrayMergingDescriptor implements ArrayMergingDescriptorInterface{
    /**
     * @var ArrayPathInterface
     */
    private $inputPath;
    /**
     * @var ArrayPathInterface
     */
    private $targetPath;
    /**
     * @var ArrayMergingStrategyInterface
     */
    private $strategie;

    /**
     * @param ArrayPathInterface $inputPath
     * @param ArrayPathInterface $targetPath
     * @param ArrayMergingStrategyInterface $strategie
     */
    function __construct(ArrayPathInterface $inputPath, ArrayPathInterface $targetPath, ArrayMergingStrategyInterface $strategie)
    {
        $this->inputPath = $inputPath;
        $this->strategie = $strategie;
        $this->targetPath = $targetPath;
    }

    /**
     * Modifies $targetArr with $targetPath according to the extracted value of $inputArr by using $this->inputPath
     * @param ArraySelectorInterface $inputArr
     * @param ArraySelectorInterface $targetArr
     * @return void
     */
    public function merge(ArraySelectorInterface $inputArr, ArraySelectorInterface $targetArr){
        $input = $inputArr->getElement($this->inputPath);
        $targetArr->merge($this->targetPath, $input, $this->strategie);
    }

}