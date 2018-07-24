<?php

namespace KoderHut\OnelogBundle;

/**
 * Interface NamedLoggerInterface
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
interface NamedLoggerInterface
{
    /**
     * Return the name of the logger
     *
     * @return string
     */
    public function getName(): string;
}
