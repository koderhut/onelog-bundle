<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\DependencyInjection\Compiler;

use KoderHut\OnelogBundle\OneLog;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class LoggerWrapPass
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class LoggerWrapPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (null === ($loggerId = $container->getParameter('onelog.logger_service'))) {
            return;
        }

        $oneLogDefinition = $container->findDefinition(OneLog::class);
        $oneLogDefinition->replaceArgument(0, new Reference($loggerId));
    }
}
