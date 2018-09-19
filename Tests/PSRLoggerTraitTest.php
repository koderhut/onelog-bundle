<?php declare(strict_types=1);

namespace KoderHut\OneLogBundle\Tests;

use KoderHut\OnelogBundle\ContextualInterface;
use KoderHut\OnelogBundle\Helper\ContextualTrait;
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
     * @test
     */
    public function testIfContextIsBeingSentCorrectly()
    {
        $exception = new MockException();
        $context = ['foo' => 'boo'];
        $exception->setContext($context);
        
        $extraContext = ['faa' => 'baa'];
        $fullContext = array_merge(['code' => MockException::CODE], $extraContext, $context);
        $this->mockLogger->error($exception, $fullContext)->shouldBeCalled();
        $this->instance->error($exception, $extraContext);
    }

    /**
     * @test
     */
    public function testProcessContext()
    {
        $exception = new MockException();
        $context = ['foo' => 'boo'];
        $exception->setContext($context);

        $expected = array_merge(['code' => MockException::CODE], $context);
        $actual = $this->instance->processContext($exception);

        $this->assertSame($expected, $actual);
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

/**
 * Class MockException
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 */
class MockException extends \Exception implements ContextualInterface
{
    use ContextualTrait;

    public const CODE = 666;

    public const MESSAGE = 'this is a message';

    public function __construct($message = self::MESSAGE, $code = self::CODE)
    {
        parent::__construct($message, $code);
    }
}
