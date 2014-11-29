<?php
namespace paslandau\DataFiltering\Evaluation;


use paslandau\BooleanExpressions\ExpressionInterface;
use paslandau\DataFiltering\Events\DataEmitterInterface;

interface DataEvaluatorInterface extends ExpressionInterface, DataEmitterInterface
{
    /**
     *
     * @todo Maybe one day PHP will support generics :(
     * @see http://stackoverflow.com/q/4553943/413531
     * @param mixed $input
     * @return mixed
     */
    public function solve($input = null);
}