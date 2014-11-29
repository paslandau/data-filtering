<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 28.08.14
 * Time: 16:17
 */

namespace paslandau\DataFiltering\Transformation;


use paslandau\ArrayUtility\ArrayPath\ArraySelectorInterface;

interface ArrayMergingDescriptorInterface {
    /**
     * Modifies $targetArr with $targetPath according to the extracted value of $inputArr by using $inputPath
     * @param ArraySelectorInterface $inputArr
     * @param ArraySelectorInterface $targetArr
     * @return void
     */
    public function merge(ArraySelectorInterface $inputArr, ArraySelectorInterface $targetArr);

} 