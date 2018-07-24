<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\Exceptions;

use KoderHut\OnelogBundle\ContextualInterface;
use KoderHut\OnelogBundle\Exceptions\ClassAlreadyRegistered;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassAlreadyRegisteredTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\Exceptions\ClassAlreadyRegistered
 */
class ClassAlreadyRegisteredTest extends TestCase
{

    /**
     * @test
     */
    public function testExceptionIsContextAware()
    {
        $instance = new ClassAlreadyRegistered('test', ['context' => 'value']);

        $this->assertInstanceOf(ContextualInterface::class, $instance);
        $this->assertEquals(['context' => 'value'], $instance->getContext());
    }
}
