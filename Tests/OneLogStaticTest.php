<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests;

use KoderHut\OnelogBundle\Helper\OneLogStatic;
use KoderHut\OnelogBundle\NamedLoggerInterface;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class OneLogStaticTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\OneLog
 */
class OneLogStaticTest extends TestCase
{
    /**
     * @var OneLog
     */
    private $instance;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $mockDefaultLogger = $this->mockTestLogger('app', 'debug', ['test', []]);
        $this->instance = new OneLog($mockDefaultLogger);
        OneLogStatic::setInstance($this->instance);
    }

    /**
     * @test
     */
    public function testCallingStaticMethodsOnInstanceProxiesTheCallToDefaultLogger()
    {
        $this->assertNull(OneLogStatic::debug('test', []));
    }

    /**
     * @test
     */
    public function testCallingStaticInstanceMethodReturnTheOneLogInstance()
    {
        $instance = OneLogStatic::instance();
        $this->assertInstanceOf(OneLog::class, $instance);

        $this->assertNull(OneLogStatic::instance()->app->debug('test', []));
    }

    /**
     * @test
     */
    public function testGetExceptionWhenOneLogIsNotInstantiatedByAccessedByStaticMethods()
    {
        OneLogStatic::destroy();
        $this->expectException(\RuntimeException::class);

        OneLogStatic::debug('test', []);
    }

    /**
     * @test
     */
    public function testGetExceptionWhenOneLogIsNotInstantiatedByTryingToRetrieveInstanceFromStatic()
    {
        OneLogStatic::destroy();
        $this->expectException(\RuntimeException::class);

        OneLogStatic::instance();
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
                $this->__call('emergency', [$message, $context]);
            }

            public function alert($message, array $context = [])
            {
                $this->__call('alert', [$message, $context]);
            }

            public function critical($message, array $context = [])
            {
                $this->__call('critical', [$message, $context]);
            }

            public function error($message, array $context = [])
            {
                $this->__call('error', [$message, $context]);
            }

            public function warning($message, array $context = [])
            {
                $this->__call('warning', [$message, $context]);
            }

            public function notice($message, array $context = [])
            {
                $this->__call('notice', [$message, $context]);
            }

            public function info($message, array $context = [])
            {
                $this->__call('info', [$message, $context]);
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

