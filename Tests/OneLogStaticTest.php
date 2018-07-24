<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests;

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
        $mockDefaultLogger = $this->mockTestLogger('app', 'debug', ['test', []], true);
        $this->instance = new OneLog($mockDefaultLogger);
    }

    /**
     * @test
     */
    public function testCallingStaticMethodsOnInstanceProxiesTheCallToDefaultLogger()
    {
        $this->assertTrue(OneLog::debug('test', []));
    }

    /**
     * @test
     */
    public function testCallingStaticInstanceMethodReturnTheOneLogInstance()
    {
        $instance = OneLog::instance();
        $this->assertInstanceOf(OneLog::class, $instance);

        $this->assertTrue(OneLog::instance()->app->debug('test', []));
    }

    /**
     * @test
     */
    public function testGetExceptionWhenOneLogIsNotInstantiatedByAccessedByStaticMethods()
    {
        $this->expectException(\RuntimeException::class);

        $nullLogger = function () {
            static::$resolved = null;
        };
        $nullLogger->call($this->instance);

        OneLog::test('test', []);
    }

    /**
     * @test
     */
    public function testGetExceptionWhenOneLogIsNotInstantiatedByTryingToRetrieveInstanceFromStatic()
    {
        $this->expectException(\RuntimeException::class);

        $nullLogger = function () {
            static::$resolved = null;
        };
        $nullLogger->call($this->instance);

        OneLog::instance();
    }

    /**
     * Create a mock logger implementing the Psr\LoggerInterface and NamedInterface
     *
     * @param $loggerName
     * @param $method
     * @param $params
     * @param $return
     *
     * @return NamedLoggerInterface|LoggerInterface
     */
    private function mockTestLogger($loggerName, $method, $params, $return)
    {
        $logger = new class($loggerName, $method, $params, $this, $return) implements NamedLoggerInterface, LoggerInterface
        {
            private $name;
            private $method;
            private $params;
            private $return;
            private $assert;

            public function __call($method, $params)
            {
                $this->assert->assertEquals($this->method, $method);
                $this->assert->assertEquals($this->params, $params);

                return $this->return;
            }

            public function __construct($name, $method, $params, $assert, $return = null)
            {
                $this->name   = $name;
                $this->method = $method;
                $this->params = $params;
                $this->return = $return;
                $this->assert = $assert;
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function emergency($message, array $context = [])
            {
                return $this->__call('', [$message, $context]);
            }

            public function alert($message, array $context = [])
            {
                return $this->__call('', [$message, $context]);
            }

            public function critical($message, array $context = [])
            {
                return $this->__call('', [$message, $context]);
            }

            public function error($message, array $context = [])
            {
                return $this->__call('', [$message, $context]);
            }

            public function warning($message, array $context = [])
            {
                return $this->__call('', [$message, $context]);
            }

            public function notice($message, array $context = [])
            {
                return $this->__call('', [$message, $context]);
            }

            public function info($message, array $context = [])
            {
                return $this->__call('', [$message, $context]);
            }

            public function debug($message, array $context = [])
            {
                return $this->__call('debug', [$message, $context]);
            }

            public function log($level, $message, array $context = [])
            {
                return $this->__call('log', [$message, $context]);
            }
        };

        return $logger;
    }
}

