<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Helper;

/**
 * Trait ContextualTrait
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
trait ContextualTrait
{
    /**
     * @var array
     */
    private $contextualData = [];

    /**
     * @inheritdoc
     */
    public function setContext(array $context): self
    {
        $this->contextualData = $context;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getContext(): array
    {
        return $this->contextualData;
    }
}