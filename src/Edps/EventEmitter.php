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
class Edps_EventEmitter implements Edps_EventEmitterInterface
{
    protected $listeners = array();

    public function on($event, $listener)
    {
        if (!is_callable($listener)) {
            throw new InvalidArgumentException('Event listener must be callable');
        }
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = array();
        }

        $this->listeners[$event][] = $listener;
    }

    public function once($event, $listener)
    {
        $onceListener = new Edps_OnceListener($this, $event, $listener);
        $this->on($event, array($onceListener, 'call'));
    }

    public function removeListener($event, $listener)
    {
        if (isset($this->listeners[$event])) {
            if (false !== $index = array_search($listener, $this->listeners[$event], true)) {
                unset($this->listeners[$event][$index]);
            }
        }
    }

    public function removeAllListeners($event = null)
    {
        if ($event !== null) {
            unset($this->listeners[$event]);
        } else {
            $this->listeners = array();
        }
    }

    public function listeners($event)
    {
        return isset($this->listeners[$event]) ? $this->listeners[$event] : array();
    }

    public function emit($event, array $arguments = array())
    {
        foreach ($this->listeners($event) as $listener) {
            call_user_func_array($listener, $arguments);
        }
    }
}
