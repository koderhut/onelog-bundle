<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Helper;

use KoderHut\OnelogBundle\ContextualInterface;

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
    public function setContext($context): self
    {
        if (is_object($context) && $context instanceof ContextualInterface) {
            $context = $context->getContext();
        } else if (!is_array($context)) {
            throw new \InvalidArgumentException();
        }

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