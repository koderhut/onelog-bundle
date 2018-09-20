<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Middleware;

use KoderHut\OnelogBundle\ContextualInterface;

/**
 * Class ExceptionCodeProcessor
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 */
class ExceptionCodeProcessor implements MiddlewareInterface
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
        if ($message instanceof \Throwable) {
            $context = array_merge($context, [
                'code' => $message->getCode(),
            ]);
        }

        return [$message, $context];
    }
}
