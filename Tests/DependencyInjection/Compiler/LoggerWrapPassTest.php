<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\DependencyInjection\Compiler;

use KoderHut\OnelogBundle\DependencyInjection\Compiler\LoggerWrapPass;
use KoderHut\OnelogBundle\Helper\NullLogger;
use KoderHut\OnelogBundle\OneLog;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class LoggerWrapPassTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\DependencyInjection\Compiler\LoggerWrapPass
 */
class LoggerWrapPassTest extends TestCase
{
    /**
     * @test
     */
    public function testPassExistsEarly()
    {
        $container = $this->getContainer(['onelog.logger_service' => null]);

        $service   = $container->findDefinition(OneLog::class);
        $arguments = $service->getArguments();

        $this->assertEquals(NullLogger::class, $arguments[0]->getClass());
    }

    /**
     * @test
     */
    public function testConfiguringOneLogService()
    {
        $container = $this->getContainer(['onelog.logger_service' => 'logger']);

        $service   = $container->findDefinition(OneLog::class);
        $arguments = $service->getArguments();

        $this->assertEquals(NullLogger::class, $arguments[0]->getClass());
    }

    /**
     * Build a test container
     *
     * @return ContainerBuilder
     */
    protected function getContainer(array $params)
    {
        $logger    = new Definition(NullLogger::class);
        $container = new ContainerBuilder();
        $loader    = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../../Resources/config'));
        $loader->load('onelog.xml');

        $container->addDefinitions(['logger' => $logger]);

        foreach ($params as $name => $value) {
            $container->setParameter($name, $value);
        }

        $container->addCompilerPass(new LoggerWrapPass());

        $container->compile();

        return $container;
    }
}
