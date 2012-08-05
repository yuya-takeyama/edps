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

class Edps_OnceListener
{
    private $parentEmitter;
    private $event;
    private $listener;

    public function __construct(Edps_EventEmitter $parentEmitter, $event, $listener)
    {
        $this->parentEmitter = $parentEmitter;
        $this->event         = $event;
        $this->listener      = $listener;
    }

    public function call()
    {
        $this->parentEmitter->removeListener($this->event, array($this, 'call'));
        $args = func_get_args();
        call_user_func_array($this->listener, $args);
    }
}
