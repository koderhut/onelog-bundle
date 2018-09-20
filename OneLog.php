<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle;

use KoderHut\OnelogBundle\Exceptions\LoggerNotFoundException;
use KoderHut\OnelogBundle\Helper\NullLogger;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * Class OneLog
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class OneLog implements LoggerInterface
{
    use LoggerTrait;

    public const DEFAULT_LOGGER = 'default';

    /**
     * @var LoggerInterface|NullLogger
     */
    private $defaultLogger;

    /**
     * @var <array>LoggerInterface
     */
    private $loggers;

    /**
     * OneLog constructor.
     *
     * @param LoggerInterface $default
     * @param LoggerInterface ...$logger
     */
    public function __construct(LoggerInterface $default = null, LoggerInterface ...$logger)
    {
        $this->defaultLogger = $default ?? new NullLogger();
        $this->registerLogger($this->defaultLogger, self::DEFAULT_LOGGER);

        array_unshift($logger, $this->defaultLogger);
        foreach ($logger as $loggerInstance) {
            $this->registerLogger($loggerInstance);
        }
    }

    /**
     * Retrieves a registered logger based on the logger name as a
     * public property of the class
     *
     * @param string $logger
     *
     * @return LoggerInterface
     */
    public function __get($logger)
    {
        if (isset($this->loggers[$logger])) {
            return $this->loggers[$logger];
        }

        throw new LoggerNotFoundException('Unable to find logger', ['logger' => $logger]);
    }

    /**
     * Returns the loggers registered
     *
     * @return array <array>LoggerInterface
     */
    public function loggers(): array
    {
        return $this->loggers;
    }

    /**
     * Register a logger instance
     *
     * @param LoggerInterface $logger
     * @param string|null     $name
     */
    public function registerLogger(LoggerInterface $logger, $name = null): void
    {
        $loggerName = $name ?? spl_object_hash($logger);
        
        if (null === $name && ($logger instanceof Logger || $logger instanceof NamedLoggerInterface)) {
            $loggerName = $logger->getName();
        }
        
        $this->loggers[$loggerName] = $logger;
    }

    /**
     * @param mixed $level
     * @param mixed $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $this->defaultLogger->log($level, $this->processMessage($message), $this->processContext($message, $context));
    }
    
    /**
     * @param mixed $message
     * @param array $context
     *
     * @return array
     */
    public function processContext($message, array $context = []): array
    {
        if ($message instanceof \Throwable) {
            $context = array_merge($context, [
                'code' => $message->getCode(),
            ]);
        }

        if ($message instanceof ContextualInterface) {
            $context = array_merge($context, $message->getContext());
        }

        return $context;
    }

    /**
     * @param mixed $message
     * 
     * @return mixed
     */
    public function processMessage($message)
    {
        if (!is_object($message)) {
            return $message;
        }

        return $message instanceof SimpleMessageInterface ? $message->getMessage() : $message;
    }
}