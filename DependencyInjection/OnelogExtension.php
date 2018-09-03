<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class OnelogExtension
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class OnelogExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('onelog.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('onelog.logger_service', $config['logger_service']);
        $container->setParameter('onelog.register_global', $config['register_global']);
        $container->setParameter('onelog.register_monolog_channels', $config['register_monolog_channels']);
        $container->setParameter('onelog.enable_request_id', $config['enable_request_id']);
    }
}
