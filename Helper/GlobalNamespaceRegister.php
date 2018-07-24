<?php declare(strict_types=1);

namespace KoderHut\OnelogBundle\Helper;

use KoderHut\OnelogBundle\Exceptions\ClassAlreadyRegistered;

/**
 * Class GlobalNamespaceRegister
 *
 * @author Denis-Florin Rendler <connect@rendler.me>
 */
class GlobalNamespaceRegister
{
    /**
     * Register a class in another namespace
     *
     * @param string        $alias
     * @param string|object $class
     *
     * @return bool
     */
    public static function register(string $alias, $class): bool
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if ($alias === $class || class_exists($alias)) {
            throw new ClassAlreadyRegistered('A class is already registered for this namespace.', [
                'class_alias' => $alias
            ]);
        }

        return class_alias($class, $alias, true);
    }
}