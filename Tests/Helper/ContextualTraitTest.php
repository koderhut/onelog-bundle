<?php declare(strict_types=1);

namespace OnelogBundle\Tests\Helper;

use KoderHut\OnelogBundle\Helper\ContextualTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextualTraitTest
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @covers \KoderHut\OnelogBundle\Helper\ContextualTrait
 */
class ContextualTraitTest extends TestCase
{
    /**
     * @test
     */
    public function testAnObjectUsingTraitIsAbleToStoreAndExposeContextInformation()
    {
        $instance = new class {
            use ContextualTrait;
        };

        $instance->setContext(['param1' => 'value1']);

        $usedTraits = class_uses($instance);
        $this->assertArrayHasKey(ContextualTrait::class, $usedTraits);
        $this->assertEquals(['param1' => 'value1'], $instance->getContext());
    }
}
