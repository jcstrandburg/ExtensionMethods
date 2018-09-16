<?php
namespace Jcstrandburg\ExtensionMethods;

/**
 * @internal
 */
class ExtensionManager
{
    public static function getExtension(string $className, string $methodName): callable
    {
        $cache = self::getClassCache($className);
        return $cache[$methodName];
    }

    public static function hasExtension(string $className, string $methodName): bool
    {
        $cache = self::getClassCache($className);
        return isset($cache[$methodName]);
    }

    public static function registerExtension(string $className, string $methodName, callable $extension)
    {
        if (!isset(self::$registeredExtensions[$className])) {
            self::$registeredExtensions[$className] = [];
        }

        if (isset(self::$registeredExtensions[$className][$methodName])) {
            throw new \RuntimeException("Extension $methodName already registered for class $className");
        }

        self::$registeredExtensions[$className][$methodName] = $extension;
        self::invalidateClassCaches();
    }

    public static function unregisterExtension(string $className, string $methodName)
    {
        unset(self::$registeredExtensions[$className][$methodName]);
        self::invalidateClassCaches();
    }

    public static function unregisterAllExtensions()
    {
        self::$registeredExtensions = [];
        self::invalidateClassCaches();
    }

    private static function getClassCache(string $className): array
    {
        if (isset(self::$classCaches[$className])) {
            return self::$classCaches[$className];
        }

        $class = new \ReflectionClass($className);
        $lineage = [];
        while ($class != null) {
            $lineage[] = $class->getName();
            $class = $class->getParentClass();
        }

        self::$classCaches[$className] = array_reduce(
            array_reverse($lineage),
            function ($cache, $nextClassName) {
                foreach ((self::$registeredExtensions[$nextClassName] ?? []) as $methodName => $extension) {
                    $cache[$methodName] = $extension;
                }

                return $cache;
            },
            []);

        return self::$classCaches[$className];
    }

    private static function invalidateClassCaches()
    {
        self::$classCaches = [];
    }

    private static $registeredExtensions = [];
    private static $classCaches = [];
}
