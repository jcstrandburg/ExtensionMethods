<?php
namespace Tests;

use Jcstrandburg\ExtensionMethods\Extensible;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function tearDown()
    {
        if (A::hasExtension('getX')) {
            A::unextend('getX');
        }
    }

    public function testExtension()
    {
        A::extend('getX', function (A $a) {
            return $a->x;
        });

        $a = new A(17, 42);
        $this->assertEquals(17, $a->getX());

        A::unextend('getX');
        $this->expectException(\BadMethodCallException::class);

        $a->getX();
    }

    public function testCannotReplaceMethodViaExtension()
    {
        $this->expectException(\RuntimeException::class);
        A::extend('getY', function (A $a) {});
    }

    public function testCannotDoubleRegisterExtensions()
    {
        A::extend('getX', function (A $a) {});

        $this->expectException(\RuntimeException::class);
        A::extend('getX', function (A $a) {});
    }
}

class A
{
    use Extensible;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getY()
    {
        return $this->y;
    }

    public $x;
    private $y;
}
