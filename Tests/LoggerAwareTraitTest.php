<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests;

use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\Helper\OneLogStatic;
use KoderHut\OnelogBundle\MiddlewareProcessor;
use KoderHut\OnelogBundle\LoggerAwareTrait;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerAwareTraitTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \Koderhut\OnelogBundle\LoggerAwareTrait
 */
class LoggerAwareTraitTest extends TestCase
{

    /**
     * @test
     */
    public function testObjectsWillReturnAnInstanceOfPsrLogger()
    {
        $instance = new class {
            use LoggerAwareTrait;

            public function __construct()
            {
                $this->loggerInstance = new NullLogger();
            }
        };

        $usedTraits = class_uses($instance);
        $this->assertArrayHasKey(LoggerAwareTrait::class, $usedTraits);
        $this->assertInstanceOf(LoggerInterface::class, $instance->logger());
    }

    /**
     * @test
     */
    public function testObjectWillAlwaysReturnALoggerInstance()
    {
        $onelog = new OneLog($this->getMockMiddlewareProcessor());
        OneLogStatic::setInstance($onelog);

        $instance = new class {
            use LoggerAwareTrait;
        };

        $this->assertInstanceOf(LoggerInterface::class, $instance->logger());
    }

    /**
     * @return MiddlewareProcessor
     */
    private function getMockMiddlewareProcessor(): MiddlewareProcessor
    {
        $middlewareProcessor = $this->prophesize(MiddlewareProcessor::class);
        $middlewareProcessor->process(Argument::any(), Argument::any(), Argument::any())->willReturn(function($args) {
            print_r($args);
            return $args;
        });

        return $middlewareProcessor->reveal();
    }
}
