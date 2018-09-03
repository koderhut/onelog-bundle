<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Tests\DependencyInjection;

use KoderHut\OnelogBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class Configuration
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class ConfigurationTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider optionsProvider
     *
     * @param $options
     * @param $expected
     */
    public function testConfigurationOptions($options, $expected)
    {
        $processor = new Processor();

        $result = $processor->processConfiguration(new Configuration(), $options);

        $this->assertEquals($expected, $result);
    }

    public function optionsProvider()
    {
        return [
            'all_configs' => [
                ['onelog' => [
                    'logger_service' => 'monolog',
                    'register_global' => true,
                    'register_monolog_channels' => false,
                ]],
                [
                    'logger_service' => 'monolog',
                    'register_global' => true,
                    'register_monolog_channels' => false,
                    'enable_request_id' => true,
                ],
            ],
            'default_configs' => [
                ['onelog' => ['logger_service' => null]],
                [
                    'logger_service' => 'logger',
                    'register_global' => false,
                    'register_monolog_channels' => false,
                    'enable_request_id' => true,
                ],
            ],
        ];
    }
}