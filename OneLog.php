<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle;

use KoderHut\OnelogBundle\Exceptions\LoggerNotFoundException;
use KoderHut\OnelogBundle\Helper\NullLogger;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class OneLog
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 *
 * @method emergency()
 * @method static emergency()
 * @method alert()
 * @method static alert()
 * @method critical()
 * @method static critical()
 * @method error()
 * @method static error()
 * @method warning()
 * @method static warning()
 * @method notice()
 * @method static notice()
 * @method info()
 * @method static info()
 * @method debug()
 * @method static debug()
 * @method log()
 * @method static log()
 *
 * @property LoggerInterface $default
 */
class OneLog
{
    public const DEFAULT_LOGGER = 'default';

    /**
     * @var OneLog|null
     */
    private static $resolved;

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

        if (self::$resolved !== $this) {
            self::$resolved = null;
            self::$resolved = $this;
        }
    }

    /**
     * @example OneLog::debug(<string>'message', <array>context)
     *
     * @param string $level
     * @param mixed  ...$params
     *
     * @return mixed
     */
    public static function __callStatic(string $level, $params)
    {
        if (!static::$resolved instanceof self) {
            throw new \RuntimeException('Logger is not properly instantiated!');
        }

        return self::$resolved->__call($level, $params);
    }

    /**
     * Returns the OneLog instance
     *
     * @return OneLog
     */
    public static function instance(): OneLog
    {
        if (self::$resolved) {
            return self::$resolved;
        }

        throw new \RuntimeException('OneLog is not properly instantiated!');
    }

    /**
     * Make sure we clear the static instance as well
     */
    public function __destruct()
    {
        self::$resolved = null;
    }

    /**
     * Proxy for logger methods on default logger instance
     *
     * @example $instance->debug(<string>'message', <array>context)
     *
     * @param string $level
     * @param array  $params
     *
     * @return bool
     */
    public function __call(string $level, array $params): bool
    {
        return $this->defaultLogger->{$level}(...$params);
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
}