<?php declare(strict_types=1);

namespace KoderHut\OneLogBundle\Tests\DependencyInjection\Compiler;

use KoderHut\OnelogBundle\DependencyInjection\Compiler\RequestIdentifierPass;
use KoderHut\OnelogBundle\Monolog\RequestIdProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RequestIdentifierPassTest extends TestCase
{

    /**
     * @test
     */
    public function testExitEarlyIfRequestIdentifierIsEnabled()
    {
        $container = $this->prophesize(ContainerBuilder::class);
        $container->hasParameter('onelog.enable_request_id')
            ->shouldBeCalled()
            ->willReturn(false)
        ;

        $instance = new RequestIdentifierPass();

        $instance->process($container->reveal());
    }

    /**
     * @test
     */
    public function testExitEarlyIfConfigParamIsMissing()
    {
        $container = $this->prophesize(ContainerBuilder::class);
        $container->hasParameter('onelog.enable_request_id')
            ->shouldBeCalled()
            ->willReturn(true)
        ;
        $container->getParameter('onelog.enable_request_id')
            ->shouldBeCalled()
            ->willReturn(true)
        ;

        $instance = new RequestIdentifierPass();

        $instance->process($container->reveal());
    }

    /**
     * @test
     */
    public function testRemoveRequestIdServiceIfConfigIsSetToFalse()
    {
        $container = $this->prophesize(ContainerBuilder::class);
        $container->hasParameter('onelog.enable_request_id')
            ->shouldBeCalled()
            ->willReturn(true)
        ;
        $container->getParameter('onelog.enable_request_id')
            ->shouldBeCalled()
            ->willReturn(false)
        ;
        $container->removeDefinition(RequestIdProcessor::class)->shouldBeCalled();

        $instance = new RequestIdentifierPass();

        $instance->process($container->reveal());
    }
}
