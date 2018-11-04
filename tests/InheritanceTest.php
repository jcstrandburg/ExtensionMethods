<?php
namespace Tests;

use Jcstrandburg\ExtensionMethods\ExtensionManager;
use PHPUnit\Framework\TestCase;

class InheritanceTest extends TestCase
{
    public function tearDown()
    {
        ExtensionManager::unregisterAllExtensions();
    }

    /**
     *  @dataProvider   classProvider
     */
    public function testExtendingChildClassDoesNotExtendBaseClass($class)
    {
        $r = rand();
        $class::extend('doX', function () use ($r) {return $r;});

        $c = new $class();
        $this->assertEquals($r, $c->doX());

        $b = new BaseTestClass();
        $this->expectException(\BadMethodCallException::class);
        $b->doX();
    }

    /**
     *  @dataProvider   classProvider
     */
    public function testBaseClass($class)
    {
        $r = rand();
        BaseTestClass::extend('doX', function () use ($r) {return $r;});

        $c = new $class();
        $this->assertEquals($r, $c->doX());

        $base = new BaseTestClass();
        $this->assertEquals($r, $base->doX());
    }

    /**
     *  @dataProvider   classProvider
     */
    public function testExtendBaseAndChildClass($class)
    {
        $r = rand();
        $s = rand();

        BaseTestClass::extend('doX', function () use ($r) {return $r;});
        $class::extend('doX', function () use ($s) {return $s;});

        $c = new $class();
        $this->assertEquals($s, $c->doX());

        $b = new BaseTestClass();
        $this->assertEquals($r, $b->doX());
    }

    public function testExtendingChildDoesNotExtendOtherChildClasss()
    {
        ChildTestClassWithTrait::extend('doX', function () {return 0;});
        ChildTestClassWithoutTrait::extend('doY', function () {return 0;});

        $this->assertFalse(ChildTestClassWithTrait::hasExtension('doY'));
        $this->assertFalse(ChildTestClassWithoutTrait::hasExtension('doX'));
    }

    public function classProvider()
    {
        return [
            [ChildTestClassWithoutTrait::class],
            [ChildTestClassWithTrait::class],
        ];
    }
}
