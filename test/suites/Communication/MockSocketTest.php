<?php
/**
 * @license see LICENSE
 */

namespace HeadlessChromium\Test\Communication;

use HeadlessChromium\Communication\Socket\MockSocket;
use PHPUnit\Framework\TestCase;

/**
 * @covers \HeadlessChromium\Communication\Channel\MockSocket
 */
class MockSocketTest extends TestCase
{

    public function testMockSocket()
    {
        $mock = new MockSocket();

        // not connected
        $this->assertFalse($mock->isConnected());
        $this->assertFalse($mock->sendData('foo'));
        $this->assertEmpty($mock->getSentData());
        $this->assertEmpty($mock->receiveData());


        // connected
        $mock->connect();

        $this->assertTrue($mock->isConnected());
        $this->assertTrue($mock->sendData('foo'));
        $this->assertEquals(['foo'], $mock->getSentData());
        $this->assertEquals(['foo'], $mock->getSentData()); // not empty until flush
        $this->assertEmpty($mock->receiveData());


        // flush sent data
        $mock->flushData();
        $this->assertEmpty($mock->getSentData());


        // with received data
        $mock->addReceivedData('bar');

        $this->assertEquals(['bar'], $mock->receiveData());
        $this->assertEmpty($mock->receiveData());


        // disconnected
        $mock->disconnect();
        $this->assertFalse($mock->isConnected());
    }
}
