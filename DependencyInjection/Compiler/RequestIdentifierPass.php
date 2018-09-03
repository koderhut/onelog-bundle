<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\DependencyInjection\Compiler;

use KoderHut\OnelogBundle\Monolog\RequestIdProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RequestIdentifierInjectorPass
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class RequestIdentifierPass implements CompilerPassInterface
{

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('onelog.enable_request_id') || true === $container->getParameter('onelog.enable_request_id')) {
            return;
        }

        $container->removeDefinition(RequestIdProcessor::class);
    }
}
