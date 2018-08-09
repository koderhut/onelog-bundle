<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Helper;

use KoderHut\OnelogBundle\OneLog;

/**
 * Class OneLogStatic
 *
 * @author Joao Jacome <969041+joaojacome@users.noreply.github.com>
 */
class OneLogStatic
{
    /**
     * @var OneLog|null
     */
    private static $resolved;

    /**
    * OneLogStatic constructor
    */
    private function __construct()
    {
    }

    /**
     * Sets the OneLog instance
     *
     * @param OneLog $resolved
     */
    public static function setInstance(OneLog $resolved)
    {
        self::$resolved = $resolved;
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
     * Unsets the instance
     */
    public static function destroy()
    {
        self::$resolved = null;
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
        if (!static::$resolved instanceof OneLog) {
            throw new \RuntimeException('Logger is not properly instantiated!');
        }

        return self::$resolved->{$level}(...$params);
    }
}
