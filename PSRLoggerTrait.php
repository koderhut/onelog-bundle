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
        $this->log('emergency', $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function alert($message, array $context = [])
    {
        $this->log('alert', $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function critical($message, array $context = [])
    {
        $this->log('critical', $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function error($message, array $context = [])
    {
        $this->log('error', $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function warning($message, array $context = [])
    {
        $this->log('warning', $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function notice($message, array $context = [])
    {
        $this->log('notice', $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->log('info', $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function debug($message, array $context = [])
    {
        $this->log('debug', $message, $context);
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
