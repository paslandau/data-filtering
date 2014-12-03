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
        $this->getDispatcher()->addListener(DataEmitterInterface::EVENT_ON_PROCESSED, $onProcessed);
    }

    /**
     * @param callable $onProcessed
     */
    public function detachOnProcessed(callable $onProcessed)
    {
        $this->getDispatcher()->removeListener(DataEmitterInterface::EVENT_ON_PROCESSED, $onProcessed);
    }

    /**
     * @param mixed $dataBefore
     * @param mixed $dataAfter
     */
    protected function emitProcessedEvent($dataBefore, $dataAfter)
    {
        $this->getDispatcher()->dispatch(DataEmitterInterface::EVENT_ON_PROCESSED, new DataProcessedEvent($this, $dataBefore, $dataAfter));
    }
} 