<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\DependencyInjection\Compiler;

use KoderHut\OnelogBundle\OneLog;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RegisterMonologChannels
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class RegisterMonologChannels implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->getParameter('onelog.register_monolog_channels') || !$container->hasDefinition($container->getParameter('onelog.logger_service'))) {
            return;
        }

        $onelogDefinition = $container->findDefinition(OneLog::class);
        $monologChannels  = preg_grep('/monolog\.logger\..*/', $container->getServiceIds());

        foreach ($monologChannels as $channelId) {
            $onelogDefinition->addMethodCall('registerLogger', [new Reference($channelId)]);
        }
    }
}
