<?php

use paslandau\DataFiltering\Events\DataEmitterInterface;
use paslandau\DataFiltering\Events\DataProcessedEvent;
use paslandau\DataFiltering\Extraction\AbstractBaseExtractor;
use paslandau\DataFiltering\Transformation\ArrayFlipTransformer;
use paslandau\DataFiltering\Transformation\DataTransformerInterface;

class AbstractBaseExtractorTest extends PHPUnit_Framework_TestCase
{
    public function test_extract()
    {
        $mock = $this->getMock(DataTransformerInterface::class);
        $f = function($data){
            return ($data+1);
        };
        $mock->expects($this->once())->method("transform")->will($this->returnCallback($f));
        /** @var DataTransformerInterface $mock */

        /** @var AbstractBaseExtractor $e */
        $e = $this->getMockForAbstractClass(AbstractBaseExtractor::class);
        $e->setTransformer($mock);

        $event = new stdClass();
        $collector = function(DataProcessedEvent $e) use (&$event){
            $event = $e;
        };
        $e->getDispatcher()->addListener(DataEmitterInterface::EVENT_ON_PROCESSED,$collector);

        $input = 1;
        $expected = $input+1;
        $actual = $e->getData($input);

        $this->assertEquals($expected,$actual,"Wrong value extracted");
        $this->assertEquals(DataProcessedEvent::class, get_class($event), "Didn't receive an event");
        /** @var DataProcessedEvent $event */
        $this->assertEquals($input,$event->getDataBefore(), "Before-Data is wrong");
        $this->assertEquals($expected,$event->getDataAfter(), "After-Data is wrong");


    }

    public function test_extractWithoutTransformer()
    {
        /** @var AbstractBaseExtractor $e */
        $e = $this->getMockForAbstractClass(AbstractBaseExtractor::class);

        $event = new stdClass();
        $collector = function(DataProcessedEvent $e) use (&$event){
            $event = $e;
        };
        $e->getDispatcher()->addListener(DataEmitterInterface::EVENT_ON_PROCESSED,$collector);

        $input = 1;
        $expected = $input;
        $actual = $e->getData($input);

        $this->assertEquals($expected,$actual,"Wrong value extracted");
        $this->assertEquals(DataProcessedEvent::class, get_class($event), "Didn't receive an event");
        /** @var DataProcessedEvent $event */
        $this->assertEquals($input,$event->getDataBefore(), "Before-Data is wrong");
        $this->assertEquals($expected,$event->getDataAfter(), "After-Data is wrong");


    }
}
