<?php

namespace paslandau\DataFiltering\Events;


interface DataEmitterInterface {
    const EVENT_ON_PROCESSED = "on_processed";

    /**
     * @param callable $onProcessed
     */
    public function attachOnProcessed(callable $onProcessed);

    /**
     * @param callable $onProcessed
     */
    public function detachOnProcessed(callable $onProcessed);
} 