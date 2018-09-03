<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle;

/**
 * Trait PSRLoggerTrait
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 */
trait PSRLoggerTrait
{

    /**
     * @param mixed $message
     * @param array $context
     */
    public function emergency($message, array $context = [])
    {
        $this->defaultLogger->emergency($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function alert($message, array $context = [])
    {
        $this->defaultLogger->alert($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function critical($message, array $context = [])
    {
        $this->defaultLogger->critical($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function error($message, array $context = [])
    {
        $this->defaultLogger->error($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function warning($message, array $context = [])
    {
        $this->defaultLogger->warning($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function notice($message, array $context = [])
    {
        $this->defaultLogger->notice($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->defaultLogger->info($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function debug($message, array $context = [])
    {
        $this->defaultLogger->debug($message, $context);
    }

    /**
     * @param mixed $level
     * @param mixed $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $this->defaultLogger->log($level, $message, $context);
    }
}
