<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests;

use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\LoggerAwareTrait;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
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
        $onelog = new OneLog();
        $instance = new class {
            use LoggerAwareTrait;
        };

        $this->assertInstanceOf(LoggerInterface::class, $instance->logger());
    }
}
