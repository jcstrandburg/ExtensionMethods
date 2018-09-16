<?php
namespace Jcstrandburg\ExtensionMethods;

trait Extensible
{
    public function __call(string $name, array $arguments)
    {
        if (static::hasExtension($name)) {
            array_unshift($arguments, $this);
            return call_user_func_array(self::$extensions[$name], $arguments);
        } else {
            $parent = get_parent_class();
            if ($parent && (method_exists($parent, $method) || method_exists($parent, '__call'))) {
                return parent::__call($method, $args);
            } else {
                throw new \BadMethodCallException('Call to undefined method ' . __CLASS__ . '::' . $name . '()');
            }
        }
    }

    public static function hasExtension(string $name): bool
    {
        return array_key_exists($name, self::$extensions);
    }

    public static function extend(string $name, callable $extension)
    {
        if (method_exists(static::class, $name)) {
            throw new \RuntimeException("Cannot replace existing method $name");
        } else if (static::hasExtension($name)) {
            throw new \RuntimeException("Extension method $name already registered");
        }

        static::$extensions[$name] = $extension;
    }

    public static function unextend(string $name)
    {
        unset(static::$extensions[$name]);
    }

    private static $extensions = [];
}
