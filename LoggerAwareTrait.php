<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle;

use Psr\Log\LoggerInterface;

/**
 * Trait LoggerAwareTrait
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
trait LoggerAwareTrait
{
    /**
     * @var LoggerInterface
     */
    private $loggerInstance;

    /**
     * @inheritdoc
     */
    public function logger(): LoggerInterface
    {
        if (null === $this->loggerInstance) {
            $this->loggerInstance = OneLog::instance()->default;
        }

        return $this->loggerInstance;
    }
}