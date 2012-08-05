<?php
/*
 * This file is part of Edps.
 *
 * (c) Igor Wiedler  <igor@wiedler.ch>
 * (c) Yuya Takeyama <sign.of.the.wolf.pentagram@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once 'Edps/EventEmitter.php';
require_once 'Edps/Tests/Listener.php';

class Edps_Tests_EventEmitterTest extends PHPUnit_Framework_TestCase
{
    private $emitter;

    public function setUp()
    {
        $this->emitter = new Edps_EventEmitter;
    }

    public function testAddListenerWithLambda()
    {
        $this->emitter->on('foo', function () {});
    }

    public function testAddListenerWithMethod()
    {
        $listener = new Edps_Tests_Listener();
        $this->emitter->on('foo', [$listener, 'onFoo']);
    }

    public function testAddListenerWithStaticMethod()
    {
        $this->emitter->on('bar', ['Edps_Tests_Listener', 'onBar']);
    }

    public function testAddListenerWithInvalidListener()
    {
        try {
            $this->emitter->on('foo', 'not a callable');
            $this->fail();
        } catch (\Exception $e) {
        }
    }

    public function testOnce()
    {
        $listenerCalled = 0;

        $this->emitter->once('foo', function () use (&$listenerCalled) {
            $listenerCalled++;
        });

        $this->assertSame(0, $listenerCalled);

        $this->emitter->emit('foo');

        $this->assertSame(1, $listenerCalled);

        $this->emitter->emit('foo');

        $this->assertSame(1, $listenerCalled);
    }

    public function testEmitWithoutArguments()
    {
        $listenerCalled = false;

        $this->emitter->on('foo', function () use (&$listenerCalled) {
            $listenerCalled = true;
        });

        $this->assertSame(false, $listenerCalled);
        $this->emitter->emit('foo');
        $this->assertSame(true, $listenerCalled);
    }

    public function testEmitWithOneArgument()
    {
        $test = $this;

        $listenerCalled = false;

        $this->emitter->on('foo', function ($value) use (&$listenerCalled, $test) {
            $listenerCalled = true;

            $test->assertSame('bar', $value);
        });

        $this->assertSame(false, $listenerCalled);
        $this->emitter->emit('foo', ['bar']);
        $this->assertSame(true, $listenerCalled);
    }

    public function testEmitWithTwoArguments()
    {
        $test = $this;

        $listenerCalled = false;

        $this->emitter->on('foo', function ($arg1, $arg2) use (&$listenerCalled, $test) {
            $listenerCalled = true;

            $test->assertSame('bar', $arg1);
            $test->assertSame('baz', $arg2);
        });

        $this->assertSame(false, $listenerCalled);
        $this->emitter->emit('foo', ['bar', 'baz']);
        $this->assertSame(true, $listenerCalled);
    }

    public function testEmitWithNoListeners()
    {
        $this->emitter->emit('foo');
        $this->emitter->emit('foo', ['bar']);
        $this->emitter->emit('foo', ['bar', 'baz']);
    }

    public function testEmitWithTwoListeners()
    {
        $listenersCalled = 0;

        $this->emitter->on('foo', function () use (&$listenersCalled) {
            $listenersCalled++;
        });

        $this->emitter->on('foo', function () use (&$listenersCalled) {
            $listenersCalled++;
        });

        $this->assertSame(0, $listenersCalled);
        $this->emitter->emit('foo');
        $this->assertSame(2, $listenersCalled);
    }

    public function testRemoveListenerMatching()
    {
        $listenersCalled = 0;

        $listener = function () use (&$listenersCalled) {
            $listenersCalled++;
        };

        $this->emitter->on('foo', $listener);
        $this->emitter->removeListener('foo', $listener);

        $this->assertSame(0, $listenersCalled);
        $this->emitter->emit('foo');
        $this->assertSame(0, $listenersCalled);
    }

    public function testRemoveListenerNotMatching()
    {
        $listenersCalled = 0;

        $listener = function () use (&$listenersCalled) {
            $listenersCalled++;
        };

        $this->emitter->on('foo', $listener);
        $this->emitter->removeListener('bar', $listener);

        $this->assertSame(0, $listenersCalled);
        $this->emitter->emit('foo');
        $this->assertSame(1, $listenersCalled);
    }

    public function testRemoveAllListenersMatching()
    {
        $listenersCalled = 0;

        $this->emitter->on('foo', function () use (&$listenersCalled) {
            $listenersCalled++;
        });

        $this->emitter->removeAllListeners('foo');

        $this->assertSame(0, $listenersCalled);
        $this->emitter->emit('foo');
        $this->assertSame(0, $listenersCalled);
    }

    public function testRemoveAllListenersNotMatching()
    {
        $listenersCalled = 0;

        $this->emitter->on('foo', function () use (&$listenersCalled) {
            $listenersCalled++;
        });

        $this->emitter->removeAllListeners('bar');

        $this->assertSame(0, $listenersCalled);
        $this->emitter->emit('foo');
        $this->assertSame(1, $listenersCalled);
    }

    public function testRemoveAllListenersWithoutArguments()
    {
        $listenersCalled = 0;

        $this->emitter->on('foo', function () use (&$listenersCalled) {
            $listenersCalled++;
        });

        $this->emitter->on('bar', function () use (&$listenersCalled) {
            $listenersCalled++;
        });

        $this->emitter->removeAllListeners();

        $this->assertSame(0, $listenersCalled);
        $this->emitter->emit('foo');
        $this->emitter->emit('bar');
        $this->assertSame(0, $listenersCalled);
    }
}
