<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Helper;

use KoderHut\OnelogBundle\NamedLoggerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class NullLogger
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class NullLogger implements NamedLoggerInterface, LoggerInterface
{
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'null_logger';
    }

    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = [])
    {
        return true;
    }
}