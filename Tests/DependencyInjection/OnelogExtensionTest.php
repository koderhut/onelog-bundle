<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\DependencyInjection;

use KoderHut\OnelogBundle\DependencyInjection\Compiler\RegisterMonologChannels;
use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class OnelogExtensionTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\DependencyInjection\OnelogExtension
 */
class OnelogExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function testRequiringLoggerServiceToDecorate()
    {
        $container = $this->getContainer([]);
        $instance  = new OnelogExtension();

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child node "logger_service" at path "onelog" must be configured.');

        $instance->load([], $container);
        $container->compile();
    }

    /**
     * @test
     */
    public function testLoggerServiceIsPushedInOneLogServiceDefinition()
    {
        $container = $this->getContainer([]);
        $instance  = new OnelogExtension();

        $instance->load(['onelog' => ['logger_service' => 'logger']], $container);

        $container->compile();

        $onelogDefinition = $container->findDefinition(OneLog::class);
        $args = $onelogDefinition->getArgument(1);

        $this->assertEquals($args->getClass(), NullLogger::class);
    }

    /**
     * Build a test container
     *
     * @param array $params
     *
     * @return ContainerBuilder
     * @throws \Exception
     */
    protected function getContainer(array $params)
    {
        $container = new ContainerBuilder();
        $loader    = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../Resources/config'));
        $loader->load('onelog.xml');

        $container->addDefinitions(['logger' => new Definition(NullLogger::class)]);

        foreach ($params as $name => $value) {
            $container->setParameter($name, $value);
        }

        return $container;
    }
}
