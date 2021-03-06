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
class Edps_Tests_CountingListener
{
    private $calledCount;

    public function __construct()
    {
        $this->calledCount = 0;
    }

    public function call()
    {
        $this->calledCount++;
    }

    public function getCalledCount()
    {
        return $this->calledCount;
    }
}
