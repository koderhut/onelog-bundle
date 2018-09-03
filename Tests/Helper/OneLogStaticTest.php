<?php declare(strict_types=1);

namespace KoderHut\OneLogBundle\Tests\Helper;

use KoderHut\OnelogBundle\Helper\OneLogStatic;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class OneLogStaticTest extends TestCase
{

    /**
     * @test
     */
    public function testThrowExceptionIfNoLoggerInstanceIsSet()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('OneLog is not properly instantiated!');

        OneLogStatic::instance();
    }

    /**
     * @test
     */
    public function testCallingPsrLoggerMethodsAreProxiedToLoggerInstance()
    {
        $logger = $this->prophesize(OneLog::class);
        $logger->debug('test', ['test'])->shouldBeCalled();

        OneLogStatic::setInstance($logger->reveal());
        OneLogStatic::debug('test', ['test']);
    }

    /**
     * @test
     */
    public function testDestroyClearsLoggerInstance()
    {
        $logger = $this->prophesize(OneLog::class);

        OneLogStatic::setInstance($logger->reveal());

        $this->assertSame($logger->reveal(), OneLogStatic::instance());

        OneLogStatic::destroy();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('OneLog is not properly instantiated!');

        OneLogStatic::instance();
    }

    /**
     * @test
     */
    public function testInstanceCannotBeCreated()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage("Call to private KoderHut\OnelogBundle\Helper\OneLogStatic::__construct() from context 'KoderHut\OneLogBundle\Tests\Helper\OneLogStaticTest'");

        $instance = new OneLogStatic();
    }
}
