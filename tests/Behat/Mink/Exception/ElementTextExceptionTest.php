<?php

namespace Tests\Behat\Mink\Exception;

use Behat\Mink\Exception\ElementTextException;

class ElementTextExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionToString()
    {
        $driver = $this->getMock('Behat\Mink\Driver\DriverInterface');
        $element = $this->getElementMock();

        $session = $this->getSessionMock();
        $session->expects($this->any())
            ->method('getDriver')
            ->will($this->returnValue($driver));
        $session->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $session->expects($this->any())
            ->method('getCurrentUrl')
            ->will($this->returnValue('http://localhost/test'));

        $element->expects($this->any())
            ->method('getText')
            ->will($this->returnValue("Hello world\nTest\n"));

        $expected = <<<'TXT'
Text error

+--[ HTTP/1.1 200 | http://localhost/test | %s ]
|
|  Hello world
|  Test
|
TXT;

        $expected = sprintf($expected.'  ', get_class($driver));

        $exception = new ElementTextException('Text error', $session, $element);

        $this->assertEquals($expected, $exception->__toString());
    }

    private function getSessionMock()
    {
        return $this->getMockBuilder('Behat\Mink\Session')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getElementMock()
    {
        return $this->getMockBuilder('Behat\Mink\Element\NodeElement')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
