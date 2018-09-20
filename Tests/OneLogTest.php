<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests;

use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\NamedLoggerInterface;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Prophecy\Argument;

/**
 * Class OneLogTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\OneLog
 */
class OneLogTest extends TestCase
{
    /**
     * @test
     */
    public function testInstantiatingANullLoggerByDefault()
    {
        $instance = new OneLog();

        $loggers = $instance->loggers();

        $this->assertCount(2, $loggers);
        $this->assertInstanceOf(NullLogger::class, array_shift($loggers));
    }

    /**
     * @test
     */
    public function testInstantiatingWithMultiplePsrLogger()
    {
        $instance = new OneLog(new NullLogger(), new NullLogger());

        $loggers = $instance->loggers();

        $this->assertCount(2, $loggers);
        $this->assertInstanceOf(NullLogger::class, array_shift($loggers));
        $this->assertInstanceOf(NullLogger::class, array_shift($loggers));
    }

    /**
     * @test
     */
    public function testCallingPsrLoggerMethodsOnInstanceAreProxiedToDefaultLogger()
    {
        $mockDefaultLogger = $this->prophesize(LoggerInterface::class);
        $mockDefaultLogger->log('debug','test', [])->shouldBeCalled()->willReturn(null);

        $instance = new OneLog($mockDefaultLogger->reveal());

        $this->assertCount(2, $instance->loggers());
        $this->assertNull($instance->debug('test', []));
    }

    /**
     * @test
     */
    public function testCallingPsrLoggerMethodsOnInstancePropertiesProxiesToSpecificLogger()
    {
        $mockLoggerDefault = $this->mockTestLogger('default', 'debug', ['test', []]);
        $mockLoggerApp     = $this->mockTestLogger('app', 'debug', ['test', []]);

        $instance = new OneLog($mockLoggerDefault, $mockLoggerApp);
        $instance->app->debug('test', []);
        $instance->default->debug('test', []);
    }

    /**
     * @test
     */
    public function testGetExceptionWhenTryingToAccessANonRegisteredLogger()
    {
        $this->expectException(\RuntimeException::class);

        $instance = new OneLog();

        $instance->test;
    }

    /**
     * Create a mock logger implementing the Psr\LoggerInterface and NamedInterface
     *
     * @param $loggerName
     * @param $method
     * @param $params
     *
     * @return NamedLoggerInterface|LoggerInterface
     */
    private function mockTestLogger($loggerName, $method, $params)
    {
        $logger = new class($loggerName, $method, $params, $this) implements NamedLoggerInterface, LoggerInterface
        {
            private $name;
            private $method;
            private $params;
            private $assert;

            public function __call($method, $params)
            {
                $this->assert->assertEquals($this->method, $method);
                $this->assert->assertEquals($this->params, $params);
            }

            public function __construct($name, $method, $params, $assert)
            {
                $this->name   = $name;
                $this->method = $method;
                $this->params = $params;
                $this->assert = $assert;
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function emergency($message, array $context = [])
            {
                $this->__call('', [$message, $context]);
            }

            public function alert($message, array $context = [])
            {
                $this->__call('', [$message, $context]);
            }

            public function critical($message, array $context = [])
            {
                $this->__call('', [$message, $context]);
            }

            public function error($message, array $context = [])
            {
                $this->__call('', [$message, $context]);
            }

            public function warning($message, array $context = [])
            {
                $this->__call('', [$message, $context]);
            }

            public function notice($message, array $context = [])
            {
                $this->__call('', [$message, $context]);
            }

            public function info($message, array $context = [])
            {
                $this->__call('', [$message, $context]);
            }

            public function debug($message, array $context = [])
            {
                $this->__call('debug', [$message, $context]);
            }

            public function log($level, $message, array $context = [])
            {
                $this->__call('log', [$message, $context]);
            }
        };

        return $logger;
    }
}


