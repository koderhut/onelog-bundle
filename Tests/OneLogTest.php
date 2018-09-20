<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests;

use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\MiddlewareProcessor;
use KoderHut\OnelogBundle\NamedLoggerInterface;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
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
        $instance = new OneLog($this->getMockMiddlewareProcessor());

        $loggers = $instance->loggers();

        $this->assertCount(2, $loggers);
        $this->assertInstanceOf(NullLogger::class, array_shift($loggers));
    }

    /**
     * @test
     */
    public function testInstantiatingWithMultiplePsrLogger()
    {
        $instance = new OneLog($this->getMockMiddlewareProcessor(), new NullLogger(), new NullLogger());

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

        $instance = new OneLog($this->getMockMiddlewareProcessor(), $mockDefaultLogger->reveal());

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

        $instance = new OneLog($this->getMockMiddlewareProcessor(), $mockLoggerDefault, $mockLoggerApp);
        $instance->app->debug('test', []);
        $instance->default->debug('test', []);
    }

    /**
     * @test
     */
    public function testGetExceptionWhenTryingToAccessANonRegisteredLogger()
    {
        $this->expectException(\RuntimeException::class);

        $instance = new OneLog($this->getMockMiddlewareProcessor());

        $instance->test;
    }

    /**
     * @return MiddlewareProcessor
     */
    private function getMockMiddlewareProcessor(): MiddlewareProcessor
    {
        $middlewareProcessor = $this->prophesize(MiddlewareProcessor::class);
        $middlewareProcessor->process(Argument::any(), Argument::any(), Argument::any())->will(function($args) {
            return [$args[1], $args[2]];
        });

        return $middlewareProcessor->reveal();
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
            use LoggerTrait;

            private $name;
            private $method;
            private $params;
            private $assert;

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

            public function log($level, $message, array $context = [])
            {
                $this->assert->assertEquals($this->method, $level);
                $this->assert->assertEquals($this->params, [$message, $context]);
            }
        };

        return $logger;
    }
}


