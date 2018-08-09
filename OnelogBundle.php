<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle;

use KoderHut\OnelogBundle\DependencyInjection\Compiler\LoggerWrapPass;
use KoderHut\OnelogBundle\DependencyInjection\Compiler\RegisterMonologChannels;
use KoderHut\OnelogBundle\Helper\GlobalNamespaceRegister;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use KoderHut\OnelogBundle\Helper\OneLogStatic;

/**
 * Class KoderHut\OnelogBundle
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class OnelogBundle extends Bundle
{
    public function boot()
    {
        if (true === $this->container->getParameter('onelog.register_global')) {
            $onelogService = $this->container->get(OneLog::class);
            OneLogStatic::setInstance($onelogService);
            GlobalNamespaceRegister::register('\\OneLog', OneLogStatic::class);
        }
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LoggerWrapPass());
        $container->addCompilerPass(new RegisterMonologChannels());
    }

}
