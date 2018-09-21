<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle;

use KoderHut\OnelogBundle\DependencyInjection\Compiler\LoggerWrapPass;
use KoderHut\OnelogBundle\DependencyInjection\Compiler\RegisterMonologChannels;
use KoderHut\OnelogBundle\DependencyInjection\Compiler\RequestIdentifierPass;
use KoderHut\OnelogBundle\Helper\GlobalNamespaceRegister;
use KoderHut\OnelogBundle\Helper\OneLogStatic;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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

        if (!empty($middlewares = $this->container->getParameter('onelog.middlewares'))) {
            $middlewareProcessor = $this->container->get(MiddlewareProcessor::class);
            foreach ($middlewares as $middleware) {
                $middlewareProcessor->registerMiddleware($this->container->get($middleware));
            }
            $this->container->get(OneLog::class)->setMiddlewareProcessor($middlewareProcessor);
        }
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LoggerWrapPass());
        $container->addCompilerPass(new RegisterMonologChannels());
        $container->addCompilerPass(new RequestIdentifierPass());
    }

}
