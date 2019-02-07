<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Middleware;

use KoderHut\OnelogBundle\ContextualInterface;

/**
 * Class ContextProcessor
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 */
class ContextProcessor implements MiddlewareInterface
{
    /**
     * @param string $level
     * @param mixed  $message
     * @param array  $context
     *
     * @return array
     */
    public function process($level, $message, $context): array
    {
        if ($message instanceof \Exception && $message->getPrevious() && $message->getPrevious() instanceof \Exception) {
            [$previousMessage, $context] = $this->process($level, $message->getPrevious(), $context);
        }

        if ($message instanceof ContextualInterface) {
            $context = array_merge($context, $message->getContext());
        }

        return [$message, $context];
    }
}
