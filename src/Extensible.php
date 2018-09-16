<?php
namespace Jcstrandburg\ExtensionMethods;

trait Extensible
{
    public function __call(string $methodName, array $arguments)
    {
        if (static::hasExtension($methodName)) {
            array_unshift($arguments, $this);
            return call_user_func_array(
                ExtensionManager::getExtension(static::class, $methodName),
                $arguments);
        } else {
            $parent = get_parent_class();
            if ($parent && (method_exists($parent, $methodName) || method_exists($parent, '__call'))) {
                return parent::__call($methodName, $arguments);
            } else {
                throw new \BadMethodCallException('Call to undefined method ' . __CLASS__ . '::' . $methodName . '()');
            }
        }
    }

    public static function hasExtension(string $methodName): bool
    {
        return ExtensionManager::hasExtension(static::class, $methodName);
    }

    public static function extend(string $methodName, callable $extension)
    {
        if (method_exists(static::class, $methodName)) {
            throw new \RuntimeException("Cannot replace existing method $methodName");
        }

        ExtensionManager::registerExtension(static::class, $methodName, $extension);
    }

    public static function unextend(string $methodName)
    {
        ExtensionManager::unregisterExtension(static::class, $methodName);
    }
}
