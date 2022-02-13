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

    public static function microtime()
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

    public static function autoSpent(\Closure $callback, $count = 1000)
    {
        $startTime = self::microtime();
        for ($i = 0; $i < $count; $i++) {
            $res = $callback();
        }
        $endTime = self::microtime();
        $spent = round(($endTime - $startTime) * 1000, 1);
        p('当前执行' . $count . '次，用时：' . $spent . '毫秒');
        return $res;
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
