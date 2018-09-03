<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Monolog;

use KoderHut\OnelogBundle\Request\IdentifierInterface;
use KoderHut\OnelogBundle\Request\Identifier as RequestId;

/**
 * Class RequestIdProcessor
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class RequestIdProcessor
{

    /**
     * @var int
     */
    private $logCount = 1;

    /**
     * @var RequestId
     */
    private $requestId;

    /**
     * RequestIdProcessor constructor.
     *
     * @param IdentifierInterface $requestId
     */
    public function __construct(IdentifierInterface $requestId = null)
    {
        $this->requestId = $requestId ?? RequestId::generate();
    }

    /**
     * Add the request id to the log entry
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record): array
    {
        $record['extra']['request_id'] = $this->requestId->identifier() . '.' . $this->logCount;
        $this->logCount++;

        return $record;
    }
}