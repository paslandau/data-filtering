<?php

namespace paslandau\DataFiltering\Events;


interface DataEmitterInterface {
    /**
     * @param callable $onProcessed
     */
    public function attachOnProcessed(callable $onProcessed);

    /**
     * @param callable $onProcessed
     */
    public function detachOnProcessed(callable $onProcessed);
} 