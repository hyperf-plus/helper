<?php

declare(strict_types=1);

namespace HPlus\Helper;

class RunTime
{
    var $StartTime = 0;
    var $StopTime = 0;

    public function __construct()
    {
        $this->start();
    }

    function get_microtime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }

    function start()
    {
        $this->StartTime = $this->get_microtime();
    }

    function stop()
    {
        $this->StopTime = $this->get_microtime();
    }

    /**
     * 返回毫秒
     * @return float
     */
    function spent()
    {
        $this->StopTime = $this->get_microtime();
        return round(($this->StopTime - $this->StartTime) * 1000, 1);
    }
}