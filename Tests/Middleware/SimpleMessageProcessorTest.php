<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\Middleware;

use KoderHut\OnelogBundle\Middleware\SimpleMessageProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleMessageProcessorTest
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 *
 * @covers \KoderHut\OnelogBundle\Middleware\SimpleMessageProcessor
 */
class SimpleMessageProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function testProcess()
    {
        $processor = new SimpleMessageProcessor();

        $simpleException = new \Exception('simple exception');

        $processed = $processor->process('debug', $simpleException, []);

        $this->assertEquals($processed[0], 'simple exception');
    }
}
