<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Middleware;

/**
 * Interface MiddlewareInterface
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 */
interface MiddlewareInterface
{
    /**
     * @param string $level
     * @param mixed  $message
     * @param array  $context
     *
     * @return array
     */
    public function process($level, $message, $context): array;
}