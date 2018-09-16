<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

class InheritanceTest extends TestCase
{
    public function tearDown()
    {
        if (ChildTestClass::hasExtension('doX')) {
            ChildTestClass::unextend('doX');
        }

        if (BaseTestClass::hasExtension('doX')) {
            BaseTestClass::unextend('doX');
        }
    }

    public function testChildClass()
    {
        ChildTestClass::extend('doX', function () {return true;});

        $c = new ChildTestClass();
        $this->assertTrue($c->doX());

        $b = new BaseTestClass();
        $this->expectException(\BadMethodCallException::class);
        $b->doX();
    }

    public function testBaseClass()
    {
        BaseTestClass::extend('doX', function () {return true;});

        $c = new ChildTestClass();
        $this->assertTrue($c->doX());

        $b = new BaseTestClass();
        $this->assertTrue($b->doX());
    }

    public function testExtendBothClasses()
    {
        BaseTestClass::extend('doX', function () {return 17;});
        ChildTestClass::extend('doX', function () {return 42;});

        $c = new ChildTestClass();
        $this->assertEquals(42, $c->doX());

        $b = new BaseTestClass();
        $this->assertEquals(17, $b->doX());
    }
}
