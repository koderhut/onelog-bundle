<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\Middleware;

use KoderHut\OnelogBundle\Middleware\StackTraceProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Class StackTraceProcessorTest
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 *
 * @covers \KoderHut\OnelogBundle\Middleware\StackTraceProcessor
 */
class StackTraceProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function testProcess()
    {
        $processor = new StackTraceProcessor();

        $parentException = new \Exception('parent exception', 204);

        try {
            throw $parentException;
        } catch (\Exception $exc) {
        }
        
        $processed = $processor->process('debug', $parentException, []);

        $this->assertNotEmpty($parentException);
        $this->assertTrue(count($processed[1]) > 0);
    }
}
