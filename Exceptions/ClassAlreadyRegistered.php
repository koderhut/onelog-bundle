<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Exceptions;

use KoderHut\OnelogBundle\ContextualInterface;
use KoderHut\OnelogBundle\Helper\ContextualTrait;

/**
 * Class ClassAlreadyRegistered
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class ClassAlreadyRegistered extends \LogicException implements ContextualInterface
{
    use ContextualTrait;

    /**
     * ClassAlreadyRegistered constructor.
     *
     * @param string    $message
     * @param array     $context
     */
    public function __construct(string $message, array $context = [])
    {
        parent::__construct($message);

        $this->setContext($context);
    }

}
