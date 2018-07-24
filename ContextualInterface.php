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
     * @param array $context
     *
     * @return mixed
     */
    public function setContext(array $context);

    /**
     * Retrieve the context of this class
     *
     * @return array
     */
    public function getContext(): array;
}