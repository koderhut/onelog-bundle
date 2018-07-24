<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Exceptions;

use KoderHut\OnelogBundle\ContextualInterface;
use KoderHut\OnelogBundle\Helper\ContextualTrait;

/**
 * Class LoggerNotFound
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class LoggerNotFoundException extends \RuntimeException implements ContextualInterface
{
    use ContextualTrait;

    /**
     * LoggerNotFoundException constructor.
     *
     * @param string $message
     * @param array  $context
     */
    public function __construct(string $message, array $context = [])
    {
        parent::__construct($message);

        $this->setContext($context);
    }
}