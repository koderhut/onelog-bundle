<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Request;

/**
 * Interface IdentifierInterface
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
interface IdentifierInterface
{

    /**
     * Return the current identifier
     *
     * @return string
     */
    public function identifier(): string;
}
