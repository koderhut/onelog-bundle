<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\Exceptions;

use KoderHut\OnelogBundle\ContextualInterface;
use KoderHut\OnelogBundle\Exceptions\LoggerNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Class LoggerNotFoundTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\Exceptions\LoggerNotFoundException
 */
class LoggerNotFoundExceptionTest extends TestCase
{

    /**
     * @test
     */
    public function testExceptionIsContextual()
    {
        $instance = new LoggerNotFoundException('test', ['context' => 'value']);

        $this->assertInstanceOf(ContextualInterface::class, $instance);
        $this->assertEquals(['context' => 'value'], $instance->getContext());
    }
}
