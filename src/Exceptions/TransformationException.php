<?php
namespace paslandau\DataFiltering\Exceptions;


class TransformationException extends \RuntimeException{

    public function __construct($message, $code = null, $previous = null){

        if($code === null){
            $code = 0;
        }
        parent::__construct($message, $code, $previous);
    }
}