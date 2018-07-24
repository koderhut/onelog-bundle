<?php declare(strict_types=1);
namespace OnelogBundle\Tests\Helper;

use KoderHut\OnelogBundle\ContextualInterface;
use KoderHut\OnelogBundle\Exceptions\ClassAlreadyRegistered;
use KoderHut\OnelogBundle\Helper\GlobalNamespaceRegister;
use PHPUnit\Framework\TestCase;

/**
 * Class GlobalNamespaceRegisterTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\Helper\GlobalNamespaceRegister
 */
class GlobalNamespaceRegisterTest extends TestCase
{

    /**
     * @test
     */
    public function testRegisteringAClassWithAGlobalAlias()
    {
        $this->assertFalse(class_exists('\\Test\\AliasTest'));
        GlobalNamespaceRegister::register('\\Test\\AliasTest', GlobalNamespaceRegister::class);

        $this->assertTrue(class_exists('\\Test\\AliasTest'));
        $this->assertInstanceOf(GlobalNamespaceRegister::class, new \Test\AliasTest());
    }

    /**
     * @test
     */
    public function testRegisteringAClassAliasUsingAnObject()
    {
        $this->assertFalse(class_exists('\\Test\\AliasTest2'));
        $object = new GlobalNamespaceRegister();
        GlobalNamespaceRegister::register('\\Test\\AliasTest2', $object);

        $this->assertTrue(class_exists('\\Test\\AliasTest2'));
        $this->assertInstanceOf(GlobalNamespaceRegister::class, $object);
    }

    /**
     * @test
     */
    public function testThrowingAnExceptionWhenRegisteringANameAlreadyExisting()
    {
        $this->expectException(ClassAlreadyRegistered::class);
        $this->expectExceptionMessage('A class is already registered for this namespace.');

        try {
            $this->assertTrue(class_exists('stdClass'));
            GlobalNamespaceRegister::register('stdClass', GlobalNamespaceRegister::class);
        } catch (ClassAlreadyRegistered $exc) {
            $this->assertInstanceOf(ContextualInterface::class, $exc);
            $this->assertEquals(['class_alias' => 'stdClass'], $exc->getContext());

            throw $exc;
        }
    }
}
