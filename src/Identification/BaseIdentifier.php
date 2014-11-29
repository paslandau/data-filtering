<?php

namespace paslandau\DataFiltering\Identification;


use paslandau\BooleanExpressions\ExpressionInterface;
use paslandau\DataFiltering\Util\StringUtil;
use paslandau\DataFiltering\Evaluation\DataEvaluatorInterface;
use paslandau\DataFiltering\Traits\LoggerTrait;

class BaseIdentifier implements IdentificationInterface {
    use LoggerTrait;

    /**
     * @var DataEvaluatorInterface
     */
    protected $evaluator;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var IdentificationInterface
     */
    protected $parent;

    /**
     * @param ExpressionInterface $evaluator
     * @param string $identifier
     * @param IdentificationInterface $parent
     */
    function __construct(ExpressionInterface $evaluator, $identifier, IdentificationInterface $parent = null)
    {
        $this->evaluator = $evaluator;
        $this->identifier = $identifier;
        $this->parent = $parent;
    }

    /**
     * @param mixed $data
     * @return boolean
     */
    public function isIdentifiedBy($data){
        $this->getLogger()->debug("Trying to identify '{$this->identifier}'...");
        if($this->parent !== null){
            if(!$this->parent->isIdentifiedBy($data)){
                $this->getLogger()->debug("Identification of '{$this->identifier}' failed. Could not identify parent '{$this->parent->getIdentifier()}'.");
                return false;
            }
        }
        $res = $this->evaluator->Solve($data);
        if($res){
            $this->getLogger()->debug("Identification of '{$this->identifier}' was successful.");
        }else{
            $this->getLogger()->debug("Identification of '{$this->identifier}' failed.");
        }
        return $res;
    }

    /**
     * @return string
     */
    public function getIdentifier(){
        return $this->identifier;
    }

    /**
     * @return string[]
     */
    public function getParentIdentifiers(){
        $parentIdentifiers = array();

        if($this->parent !== null){
            $parentIdentifier = $this->parent->getIdentifier();
            $parentIdentifiers = $this->parent->getParentIdentifiers();
            $parentIdentifiers = array_merge([$parentIdentifier], $parentIdentifiers);
        }
        return $parentIdentifiers;
    }

    /**
     * @param \paslandau\DataFiltering\Evaluation\DataEvaluatorInterface $evaluator
     */
    public function setEvaluator($evaluator)
    {
        $this->evaluator = $evaluator;
    }

    /**
     * @return \paslandau\DataFiltering\Evaluation\DataEvaluatorInterface
     */
    public function getEvaluator()
    {
        return $this->evaluator;
    }

    public function __toString()
    {
        $ss = StringUtil::GetObjectString($this);
        return $ss;
    }
}