<?php declare(strict_types=1);

namespace KoderHut\OneLogBundle\Tests\Monolog;

use KoderHut\OnelogBundle\Monolog\RequestIdProcessor;
use KoderHut\OnelogBundle\Request\IdentifierInterface;
use PHPUnit\Framework\TestCase;

class RequestIdProcessorTest extends TestCase
{

    /**
     * @test
     */
    public function testInstanceCreatesDateTimeBasedIdWhenNotProvided()
    {
        $dateTime = new \DateTime();
        $date = $dateTime->format('Ymd');
        $time = $dateTime->format('Hi');
        $format = "${date}\.${time}[0-5]{1}[0-9]{1}\.[0-9]{6}\.1";

        $instance = new RequestIdProcessor();

        $result = $instance([]);

        $this->assertArrayHasKey('extra', $result);
        $this->assertArrayHasKey('request_id', $result['extra']);
        $this->assertRegExp("/${format}/", $result['extra']['request_id']);
    }

    /**
     * @test
     */
    public function testInstanceAcceptSpecificIdentifier()
    {
        $identifier = $this->prophesize(IdentifierInterface::class);
        $identifier->identifier()->shouldBeCalled()->willReturn('test');
        $instance = new RequestIdProcessor($identifier->reveal());

        $result = $instance([]);
        $this->assertArrayHasKey('extra', $result);
        $this->assertArrayHasKey('request_id', $result['extra']);
        $this->assertEquals('test.1', $result['extra']['request_id']);
    }

    /**
     * @test
     */
    public function testMultipleLogEntriesWillBeMarkedIncrementally()
    {
        $identifier = $this->prophesize(IdentifierInterface::class);
        $identifier->identifier()->shouldBeCalled()->willReturn('test');
        $instance = new RequestIdProcessor($identifier->reveal());

        $result = $instance([]);
        $this->assertArrayHasKey('extra', $result);
        $this->assertArrayHasKey('request_id', $result['extra']);
        $this->assertEquals('test.1', $result['extra']['request_id']);

        $result = $instance([]);
        $this->assertArrayHasKey('extra', $result);
        $this->assertArrayHasKey('request_id', $result['extra']);
        $this->assertEquals('test.2', $result['extra']['request_id']);
    }
}
