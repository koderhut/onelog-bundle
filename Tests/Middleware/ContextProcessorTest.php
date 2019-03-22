<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\Middleware;

use KoderHut\OnelogBundle\Middleware\ContextProcessor;
use PHPUnit\Framework\TestCase;
use KoderHut\OnelogBundle\Helper\ContextualTrait;
use KoderHut\OnelogBundle\ContextualInterface;

/**
 * Class ContextProcessorTest
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 *
 * @covers \KoderHut\OnelogBundle\Middleware\ContextProcessor
 */
class ContextProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function testProcessWithException()
    {
        $processor = new ContextProcessor();

        $exception = new class extends \Exception implements ContextualInterface {
            use ContextualTrait;  
        };
        $exception->setContext(['this is' => 'the context']);
        
        $processed = $processor->process('debug', $exception, []);

        $this->assertEquals([
            $exception,
            [
                'this is' => 'the context'
            ]],
            $processed
        );
    }

    /**
     * @test
     */
    public function testProcessWithExceptionAndContext()
    {
        $processor = new ContextProcessor();

        $exception = new class extends \Exception implements ContextualInterface {
            use ContextualTrait;  
        };
        $exception->setContext(['this is' => 'the context']);
        
        $processed = $processor->process('debug', $exception, ['and this is' => 'another context']);

        $this->assertEquals([
            $exception,
            [
                'this is' => 'the context',
                'and this is' => 'another context',
            ]],
            $processed
        );
    }

    /**
     * @test
     */
    public function testProcessWithNestedException()
    {
        $processor = new ContextProcessor();

        $grandChildException = new class('grand child exception') extends \Exception implements ContextualInterface {
            use ContextualTrait;  
        };
        $childException = new class('child exception', 0, $grandChildException) extends \Exception implements ContextualInterface {
            use ContextualTrait;  
        };
        $parentException = new class('parent exception', 0, $childException) extends \Exception implements ContextualInterface {
            use ContextualTrait;  
        };

        $parentException->setContext(['this is1' => '1the context']);
        $childException->setContext(['this is2' => '2the context']);
        $grandChildException->setContext(['this is3' => '3the context']);
        
        $processed = $processor->process('debug', $parentException, ['and this is' => 'another context']);

        $this->assertEquals([
            $parentException,
            [
                'and this is' => 'another context',
                'this is1' => '1the context',
                'this is2' => '2the context',
                'this is3' => '3the context',
            ]],
            $processed
        );
    }

    /**
     * @test
     */
    public function testProcessWithClassContext()
    {
        $processor = new ContextProcessor();

        $classWithContext = new class() implements ContextualInterface {
            use ContextualTrait;  
        };

        $classWithContext->setContext(['this is1' => '1the context']);
        
        $processed = $processor->process('debug', $classWithContext, []);

        $this->assertEquals([
            $classWithContext,
            [
                'this is1' => '1the context',
            ]],
            $processed
        );
    }

    /**
     * @test
     */
    public function testProcessWithNestedClassContext()
    {
        $processor = new ContextProcessor();

        $classWithContext = new class() implements ContextualInterface {
            use ContextualTrait;  
        };

        $classWithContext->setContext(['this is1' => '1the context']);

        $secondClassWithContext = new class() implements ContextualInterface {
            use ContextualTrait;  
        };

        $secondClassWithContext->setContext($classWithContext);
        
        
        $processed = $processor->process('debug', $secondClassWithContext, []);

        $this->assertEquals([
            $secondClassWithContext,
            [
                'this is1' => '1the context',
            ]],
            $processed
        );
    }
}
