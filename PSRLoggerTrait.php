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
        $this->defaultLogger->emergency($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function alert($message, array $context = [])
    {
        $this->defaultLogger->alert($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function critical($message, array $context = [])
    {
        $this->defaultLogger->critical($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function error($message, array $context = [])
    {
        $this->defaultLogger->error($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function warning($message, array $context = [])
    {
        $this->defaultLogger->warning($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function notice($message, array $context = [])
    {
        $this->defaultLogger->notice($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->defaultLogger->info($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $message
     * @param array $context
     */
    public function debug($message, array $context = [])
    {
        $this->defaultLogger->debug($message, $this->processContext($message, $context));
    }

    /**
     * @param mixed $level
     * @param mixed $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $this->defaultLogger->log($level, $message, $this->processContext($message, $context));
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
}
