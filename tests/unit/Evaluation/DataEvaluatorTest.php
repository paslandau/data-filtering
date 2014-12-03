<?php

use paslandau\ComparisonUtility\ComparisonObjectInterface;
use paslandau\DataFiltering\Evaluation\DataEvaluator;
use paslandau\DataFiltering\Events\DataEmitterInterface;
use paslandau\DataFiltering\Events\DataProcessedEvent;
use paslandau\DataFiltering\Extraction\DataExtractorInterface;

class DataEvaluatorTest extends PHPUnit_Framework_TestCase
{
    public function test_extract()
    {
        $dataBefore = "getData";
        $dataAfter = "compareToExpected";

        $mock = $this->getMock(DataExtractorInterface::class);
        $mock->expects($this->once())->method("getData")->will($this->returnValue($dataBefore));
        /** @var DataExtractorInterface $mock */

        $compMock = $this->getMock(ComparisonObjectInterface::class);
        $compMock->expects($this->once())->method("compareToExpected")->will($this->returnValue($dataAfter));
        /** @var ComparisonObjectInterface $compMock */

        $e = new DataEvaluator($compMock, $mock);


        $event = new stdClass();
        $collector = function (DataProcessedEvent $e) use (&$event) {
            $event = $e;
        };
        $e->getDispatcher()->addListener(DataEmitterInterface::EVENT_ON_PROCESSED, $collector);

        $expected = $dataAfter;
        $actual = $e->solve(null);

        $this->assertEquals($expected, $actual, "Wrong value returned");
        $this->assertEquals(DataProcessedEvent::class, get_class($event), "Didn't receive an event");
        /** @var DataProcessedEvent $event */
        $this->assertEquals($dataBefore, $event->getDataBefore(), "Before-Data is wrong");
        $this->assertEquals($dataAfter, $event->getDataAfter(), "After-Data is wrong");


    }
}
