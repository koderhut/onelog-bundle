<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\Middleware;

use KoderHut\OnelogBundle\Middleware\ExceptionCodeProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Class ExceptionCodeProcessorTest
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 *
 * @covers \KoderHut\OnelogBundle\Middleware\ExceptionCodeProcessor
 */
class ExceptionCodeProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function testProcessWithException()
    {
        $processor = new ExceptionCodeProcessor();

        $parentException = new \Exception('parent exception', 204);
        
        $processed = $processor->process('debug', $parentException, []);

        $this->assertEquals([
            $parentException,
            [
                'code' => 204
            ]],
            $processed
        );
    }

    /**
     * @test
     */
    public function testProcessWithNestedExceptions()
    {
        $processor = new ExceptionCodeProcessor();

        $grandChildException = new \Exception('grand child exception', 202);
        $childException = new \Exception('child exception', 203, $grandChildException);
        $parentException = new \Exception('parent exception', 204, $childException);
        
        $processed = $processor->process('debug', $parentException, []);

        $this->assertEquals([
            $parentException,
            [
                'codes' => [204, 203, 202],
                'code' => 204
            ]],
            $processed
        );
    }
}
