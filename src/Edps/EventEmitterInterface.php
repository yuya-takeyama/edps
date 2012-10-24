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
interface Edps_EventEmitterInterface
{
    public function on($event, $listener);
    public function once($event, $listener);
    public function removeListener($event, $listener);
    public function removeAllListeners($event = null);
    public function listeners($event);
    public function emit($event, array $arguments = array());
}
