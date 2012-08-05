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
require_once 'Edps/Tests/CountingListener.php';

class Edps_Tests_EventEmitterTest extends PHPUnit_Framework_TestCase
{
    private $emitter;

    public function setUp()
    {
        $this->emitter = new Edps_EventEmitter;
    }

    public function testAddListenerWithLambda()
    {
        $this->emitter->on('foo', create_function('', ''));
    }

    public function testAddListenerWithMethod()
    {
        $listener = new Edps_Tests_Listener();
        $this->emitter->on('foo', array($listener, 'onFoo'));
    }

    public function testAddListenerWithStaticMethod()
    {
        $this->emitter->on('bar', array('Edps_Tests_Listener', 'onBar'));
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
        $listener = new Edps_Tests_CountingListener;
        $this->emitter->once('foo', array($listener, 'call'));

        $this->assertSame(0, $listener->getCalledCount());

        $this->emitter->emit('foo');

        $this->assertSame(1, $listener->getCalledCount());

        $this->emitter->emit('foo');

        $this->assertSame(1, $listener->getCalledCount());
    }

    public function testEmitWithoutArguments()
    {
        $listener = new Edps_Tests_CountingListener;
        $this->emitter->on('foo', array($listener, 'call'));

        $this->assertSame(0, $listener->getCalledCount());
        $this->emitter->emit('foo');
        $this->assertSame(1, $listener->getCalledCount());
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
        $this->emitter->emit('foo', array('bar'));
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
        $this->emitter->emit('foo', array('bar', 'baz'));
        $this->assertSame(true, $listenerCalled);
    }

    public function testEmitWithNoListeners()
    {
        $this->emitter->emit('foo');
        $this->emitter->emit('foo', array('bar'));
        $this->emitter->emit('foo', array('bar', 'baz'));
    }

    public function testEmitWithTwoListeners()
    {
        $listener = new Edps_Tests_CountingListener;
        $this->emitter->on('foo', array($listener, 'call'));

        $this->emitter->on('foo', array($listener, 'call'));

        $this->assertSame(0, $listener->getCalledCount());
        $this->emitter->emit('foo');
        $this->assertSame(2, $listener->getCalledCount());
    }

    public function testRemoveListenerMatching()
    {
        $listener = new Edps_Tests_CountingListener;
        $listenerMethod = array($listener, 'call');

        $this->emitter->on('foo', $listenerMethod);
        $this->emitter->removeListener('foo', $listenerMethod);

        $this->assertSame(0, $listener->getCalledCount());
        $this->emitter->emit('foo');
        $this->assertSame(0, $listener->getCalledCount());
    }

    public function testRemoveListenerNotMatching()
    {
        $listener = new Edps_Tests_CountingListener;
        $listenerMethod = array($listener, 'call');

        $this->emitter->on('foo', $listenerMethod);
        $this->emitter->removeListener('bar', $listenerMethod);

        $this->assertSame(0, $listener->getCalledCount());
        $this->emitter->emit('foo');
        $this->assertSame(1, $listener->getCalledCount());
    }

    public function testRemoveAllListenersMatching()
    {
        $listener = new Edps_Tests_CountingListener;
        $this->emitter->on('foo', array($listener, 'call'));

        $this->emitter->removeAllListeners('foo');

        $this->assertSame(0, $listener->getCalledCount());
        $this->emitter->emit('foo');
        $this->assertSame(0, $listener->getCalledCount());
    }

    public function testRemoveAllListenersNotMatching()
    {
        $listener = new Edps_Tests_CountingListener;
        $this->emitter->on('foo', array($listener, 'call'));

        $this->emitter->removeAllListeners('bar');

        $this->assertSame(0, $listener->getCalledCount());
        $this->emitter->emit('foo');
        $this->assertSame(1, $listener->getCalledCount());
    }

    public function testRemoveAllListenersWithoutArguments()
    {
        $listener = new Edps_Tests_CountingListener;
        $listenerMethod = array($listener, 'call');
        $this->emitter->on('foo', $listenerMethod);

        $this->emitter->on('bar', $listenerMethod);

        $this->emitter->removeAllListeners();

        $this->assertSame(0, $listener->getCalledCount());
        $this->emitter->emit('foo');
        $this->emitter->emit('bar');
        $this->assertSame(0, $listener->getCalledCount());
    }
}
