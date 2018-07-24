<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests;

use KoderHut\OnelogBundle\DependencyInjection\Compiler\LoggerWrapPass;
use KoderHut\OnelogBundle\DependencyInjection\Compiler\RegisterMonologChannels;
use KoderHut\OnelogBundle\DependencyInjection\OnelogExtension;
use KoderHut\OnelogBundle\OnelogBundle;
use Nyholm\BundleTest\BaseBundleTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class OnelogBundleTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\OnelogBundle
 */
class OnelogBundleTest extends BaseBundleTestCase
{
    /**
     * @inheritDoc
     */
    protected function getBundleClass()
    {
        return OnelogBundle::class;
    }

    /**
     * @test
     */
    public function testRegisterCompilerPasses()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->registerExtension(new OnelogExtension());

        $instance = new OnelogBundle();
        $instance->build($containerBuilder);

        $passes = $containerBuilder->getCompilerPassConfig()->getBeforeOptimizationPasses();

        $passClasses = [];
        foreach ($passes as $pass) {
            $passClasses[] = get_class($pass);
        }

        $this->assertContains(LoggerWrapPass::class, $passClasses);
        $this->assertContains(RegisterMonologChannels::class, $passClasses);
    }

    /**
     * @test
     */
    public function testRegisterOnelogGlobally()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Functional/config/config.yaml');

        $this->assertFalse(class_exists('\\OneLog'));

        $this->bootKernel();

        $this->assertTrue(class_exists('\\OneLog'));
    }
}
