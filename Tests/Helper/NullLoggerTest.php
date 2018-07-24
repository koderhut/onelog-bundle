<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\Helper;

use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\NamedLoggerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class NullLoggerTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\Helper\NullLogger
 */
class NullLoggerTest extends TestCase
{
    /**
     * @test
     */
    public function testNameIsNullLoggerAndItImplementsNamedLoggerInterfaceAndPsrLoggerInterface()
    {
        $instance = new NullLogger();

        $this->assertInstanceOf(NamedLoggerInterface::class, $instance);
        $this->assertInstanceOf(LoggerInterface::class, $instance);
        $this->assertEquals('null_logger', $instance->getName());
    }

    /**
     * @test
     */
    public function testNullLoggerReturnAlwaysTrue()
    {
        $instance = new NullLogger();

        $this->assertTrue($instance->emergency('test'));
        $this->assertTrue($instance->alert('test'));
        $this->assertTrue($instance->critical('test'));
        $this->assertTrue($instance->error('test'));
        $this->assertTrue($instance->warning('test'));
        $this->assertTrue($instance->notice('test'));
        $this->assertTrue($instance->info('test'));
        $this->assertTrue($instance->debug('test'));
        $this->assertTrue($instance->log('test', 'test'));
    }
}
