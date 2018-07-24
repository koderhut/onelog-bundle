<?php

namespace KoderHut\OnelogBundle;

use Psr\Log\LoggerInterface;

/**
 * Interface LoggerAwareInterface
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
interface LoggerAwareInterface
{
    /**
     * Returns a OneLog instance
     *
     * @return LoggerInterface
     */
    public function logger(): LoggerInterface;
}