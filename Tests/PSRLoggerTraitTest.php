<?php declare(strict_types=1);

namespace KoderHut\OneLogBundle\Tests;

use KoderHut\OnelogBundle\PSRLoggerTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PSRLoggerTraitTest extends TestCase
{
    /**
     * @var LoggerInterface;
     */
    protected $mockLogger;

    /**
     * @var PSRLoggerTrait
     */
    protected $instance;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->mockLogger = $this->prophesize(LoggerInterface::class);
        $this->instance   = new MockPSRLoggerTrait($this->mockLogger->reveal());
    }

    /**
     * @test
     *
     * @dataProvider loggerMethods
     *
     * @param string $method
     */
    public function testMethodsAreDispatchedToLogger(string $method)
    {
        $this->mockLogger->{$method}('test', [])->shouldBeCalled();

        $this->instance->{$method}('test', []);
    }

    /**
     * @test
     */
    public function testCallingLogIsProxiedToLogger()
    {
        $this->mockLogger->log('alert', 'test', [])->shouldBeCalled();

        $this->instance->log('alert', 'test', []);
    }
    /**
     * @see testMethodsAreDispatchedToLogger
     *
     * @return array
     */
    public function loggerMethods()
    {
        return [
            'emergency' => ['emergency'],
            'alert'     => ['alert'],
            'critical'  => ['critical'],
            'error'     => ['error'],
            'warning'   => ['warning'],
            'notice'    => ['notice'],
            'info'      => ['info'],
            'debug'     => ['debug'],
        ];
    }
}

/**
 * Class MockPSRLoggerTrait
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class MockPSRLoggerTrait
{
    use PSRLoggerTrait;

    protected $defaultLogger;

    public function __construct($logger)
    {
        $this->defaultLogger = $logger;
    }
}
