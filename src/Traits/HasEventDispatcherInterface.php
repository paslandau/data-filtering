<?php

namespace paslandau\DataFiltering\Traits;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface HasEventDispatcherInterface {

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher();
}