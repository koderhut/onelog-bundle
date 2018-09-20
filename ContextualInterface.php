<?php

namespace KoderHut\OnelogBundle;

/**
 * Interface ContextualInterface
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
interface ContextualInterface
{
    /**
     * Set the context for the logger
     *
     * @param mixed $context
     *
     * @return mixed
     */
    public function setContext($context);

    /**
     * Retrieve the context of this class
     *
     * @return array
     */
    public function getContext(): array;
}