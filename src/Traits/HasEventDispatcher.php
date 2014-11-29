<?php

namespace paslandau\DataFiltering\Traits;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait HasEventDispatcher {
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function getDispatcher(){
        if($this->dispatcher === null){
            $this->dispatcher = new EventDispatcher();
        }
        return $this->dispatcher;
    }
}