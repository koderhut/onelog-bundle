<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class OneLog
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $root = $treeBuilder->root('onelog');

        $root
            ->children()
                ->scalarNode('logger_service')
                    ->example('logger_service: monolog')
                    ->info('The logger service to wrap. This will be used as the default logger')
                    ->isRequired()
                    ->treatNullLike('logger')
                    ->defaultValue('logger')
                ->end()
                ->booleanNode('register_global')
                    ->info('Register the OneLog class in the global namespace')
                    ->defaultFalse()
                    ->treatNullLike(false)
                ->end()
                ->booleanNode('register_monolog_channels')
                    ->info('Register the Monolog channels as OneLog properties')
                    ->defaultFalse()
                    ->treatNullLike(false)
                ->end()
                ->booleanNode('enable_request_id')
                    ->info('Add a request identifier to all log entries. Allows for easier tracking of logs during a request')
                    ->defaultTrue()
                    ->treatNullLike(true)
                ->end()
            ->end();

        return $treeBuilder;
    }

}