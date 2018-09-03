<?php declare(strict_types=1);

namespace KoderHut\OneLogBundle\Tests\Request;

use KoderHut\OnelogBundle\Request\Identifier;
use KoderHut\OnelogBundle\Request\IdentifierInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class IdentifierTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class IdentifierTest extends TestCase
{

    /**
     * @test
     */
    public function testCanProvideSpecificIdentifierString()
    {
        $instance = new Identifier('test');

        $this->assertEquals('test', $instance->identifier());
    }

    /**
     * @test
     */
    public function testGeneratingInstanceFromStaticCall()
    {
        $dateTime = new \DateTime();
        $date = $dateTime->format('Ymd');
        $time = $dateTime->format('Hi');
        $format = "${date}\.${time}[0-5]{1}[0-9]{1}\.[0-9]{6}";
        $instance = Identifier::generate();

        $this->assertInstanceOf(IdentifierInterface::class, $instance);
        $this->assertRegExp("/${format}/", $instance->identifier());
    }

    /**
     * @test
     */
    public function testAddingSaltsToIdentifier()
    {
        $dateTime = new \DateTime();
        $date = $dateTime->format('Ymd');
        $time = $dateTime->format('Hi');
        $format = "${date}\.${time}[0-5]{1}[0-9]{1}\.[0-9]{6}\.test1\.test2";
        $instance = Identifier::generate('test1', 'test2');

        $this->assertInstanceOf(IdentifierInterface::class, $instance);
        $this->assertRegExp("/${format}/", $instance->identifier());
    }
}
