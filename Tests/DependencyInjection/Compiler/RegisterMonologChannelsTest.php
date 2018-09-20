<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\DependencyInjection\Compiler;

use KoderHut\OnelogBundle\DependencyInjection\Compiler\RegisterMonologChannels;
use KoderHut\OnelogBundle\DependencyInjection\OnelogExtension;
use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RegisterMonologChannelsTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\DependencyInjection\Compiler\RegisterMonologChannels
 */
class RegisterMonologChannelsTest extends TestCase
{

    /**
     * @test
     */
    public function testExitEarlyIfNotEnabled()
    {
        $container = $this->getContainer(['logger_service' => 'monolog.logger']);
        $container->compile();

        $this->assertTrue($container->hasParameter('onelog.register_monolog_channels'));
        $this->assertFalse($container->getParameter('onelog.register_monolog_channels'));

        $onelogDefinition = $container->getDefinition(OneLog::class);
        $args             = $onelogDefinition->getArguments();
        $methodCalls      = $onelogDefinition->getMethodCalls();

        $this->assertCount(2, $args);
        $this->assertCount(0, $methodCalls);
    }

    /**
     * @test
     */
    public function testExitEarlyIfMonologServiceIsNotDefined()
    {
        $container = $this->getContainer(['logger_service' => 'monolog.logger']);
        $container->removeDefinition('monolog.logger');
        $container->compile();

        $this->assertTrue($container->hasParameter('onelog.register_monolog_channels'));
        $this->assertFalse($container->getParameter('onelog.register_monolog_channels'));

        $onelogDefinition = $container->getDefinition(OneLog::class);
        $args             = $onelogDefinition->getArguments();
        $methodCalls      = $onelogDefinition->getMethodCalls();

        $this->assertCount(2, $args);
        $this->assertCount(0, $methodCalls);
    }

    /**
     * @test
     */
    public function testRegisterAllMonologChannels()
    {
        $container = $this->getContainer(['logger_service' => 'monolog.logger', 'register_monolog_channels' => true]);
        $container->compile();

        $this->assertTrue($container->hasParameter('onelog.register_monolog_channels'));
        $this->assertTrue($container->getParameter('onelog.register_monolog_channels'));

        $onelogDefinition = $container->getDefinition(OneLog::class);
        $args             = $onelogDefinition->getArguments();
        $methodCalls      = $onelogDefinition->getMethodCalls();

        $this->assertCount(2, $args);
        $this->assertCount(2, $methodCalls);

        foreach ($methodCalls as $key => $call) {
            $this->assertInternalType('array', $call);
            $this->assertInstanceOf(Reference::class, $call[1][0]);
            $this->assertEquals('monolog.logger.test' . $key, $call[1][0]->__toString());
        }
    }

    /**
     * Build a test container
     *
     * @param array $params
     *
     * @return ContainerBuilder
     */
    protected function getContainer(array $params)
    {
        $logger    = new Definition(NullLogger::class);
        $logger->setPublic(true);
        $container = new ContainerBuilder();
        $loader    = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../../Resources/config'));
        $loader->load('onelog.xml');

        $container->addDefinitions(['monolog.logger' => $logger]);
        $container->addDefinitions(['monolog.logger.test0' => $logger]);
        $container->addDefinitions(['monolog.logger.test1' => $logger]);
        $container->registerExtension(new OnelogExtension());
        $container->loadFromExtension('onelog', $params);
        $container->addCompilerPass(new RegisterMonologChannels());

        return $container;
    }
}
