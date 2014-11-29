<?php

namespace paslandau\DataFiltering\Events;


use Symfony\Component\EventDispatcher\Event;

class DataProcessedEvent extends Event
{

    /**
     * @var boolean
     */
    private $propagationStopped;

    /**
     * @var DataEmitterInterface
     */
    private $emitter;

    /**
     * @var mixed
     */
    private $dataBefore;

    /**
     * @var mixed
     */
    private $dataAfter;

    /**
     * @param DataEmitterInterface $emitter
     * @param mixed $dataAfter
     * @param mixed $dataBefore
     */
    function __construct(DataEmitterInterface $emitter, $dataBefore, $dataAfter)
    {
        $this->emitter = $emitter;
        $this->dataAfter = $dataAfter;
        $this->dataBefore = $dataBefore;
        $this->propagationStopped = false;
    }

    /**
     * @return mixed
     */
    public function getDataAfter()
    {
        return $this->dataAfter;
    }

    /**
     * @return mixed
     */
    public function getDataBefore()
    {
        return $this->dataBefore;
    }

    /**
     * @return DataEmitterInterface
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

    /**
     * @return void
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

    /**
     * @return void
     */
    public function resumePropagation()
    {
        $this->propagationStopped = false;
    }

    /**
     * @return boolean
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }

}