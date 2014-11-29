<?php

namespace paslandau\DataFiltering\Events;

use paslandau\DataFiltering\Traits\HasEventDispatcher;

trait DataEmitterTrait
{
    use HasEventDispatcher;

    private $onProcessed = array();

    /**
     * @param callable $onProcessed
     */
    public function attachOnProcessed(callable $onProcessed)
    {
        $this->getDispatcher()->addListener("on_processed", $onProcessed);
    }

    /**
     * @param callable $onProcessed
     */
    public function detachOnProcessed(callable $onProcessed)
    {
        $this->getDispatcher()->removeListener("on_processed", $onProcessed);
    }

    /**
     * @param mixed $dataBefore
     * @param mixed $dataAfter
     */
    protected function emitProcessedEvent($dataBefore, $dataAfter)
    {
        $this->getDispatcher()->dispatch("on_processed", new DataProcessedEvent($this, $dataBefore, $dataAfter));
    }
} 