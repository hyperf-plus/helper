<?php
declare(strict_types=1);


use Hyperf\Cache\CacheManager;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Redis\RedisFactory;
use Hyperf\Context\Context;
use Psr\Http\Message\ServerRequestInterface;
use HPlus\Helper\DbHelper\QueryHelper;
use Psr\SimpleCache\CacheInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

if (!function_exists('redis')) {
    /**
     * Redis
     * @param string $name
     * @return \Hyperf\Redis\RedisProxy|Redis
     */
    function redis($name = 'default')
    {
        return ApplicationContext::getContainer()->get(RedisFactory::class)->get($name);
    }
}

if (!function_exists('Logger')) {
    /**
     * Redis
     * @return StdoutLoggerInterface
     */
    function Logger()
    {
        return ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
    }
}

if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        /**
         * @var ServerRequestInterface $request
         */
        $request = Context::get(ServerRequestInterface::class);

        if (empty($request)) {
            return '0.0.0.0';
        }
        
        $ip_addr = $request->getHeaderLine('x-forwarded-for');
        if (verify_ip($ip_addr)) {
            return $ip_addr;
        }
        $ip_addr = $request->getHeaderLine('remote-host');
        if (verify_ip($ip_addr)) {
            return $ip_addr;
        }
        $ip_addr = $request->getHeaderLine('x-real-ip');
        if (verify_ip($ip_addr)) {
            return $ip_addr;
        }
        $ip_addr = $request->getServerParams()['remote_addr'] ?? '0.0.0.0';
        if (verify_ip($ip_addr)) {
            return $ip_addr;
        }
        return '0.0.0.0';
    }
}


if (!function_exists('get_container')) {
    function get_container($id)
    {
        return ApplicationContext::getContainer()->get($id);
    }
}

if (!function_exists('verify_ip')) {
    function verify_ip($realip)
    {
        return filter_var($realip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
}
//输出控制台日志
if (!function_exists('p')) {
    function p($val, $title = null, $starttime = '')
    {
        print_r('[ ' . date("Y-m-d H:i:s") . ']:');
        if ($title != null) {
            print_r($title);
        }
        print_r($val);
        print_r("\r\n");
    }
}

if (!function_exists('uuid')) {
    function uuid($length)
    {
        if (function_exists('random_bytes')) {
            $uuid = bin2hex(\random_bytes($length));
        } else if (function_exists('openssl_random_pseudo_bytes')) {
            $uuid = bin2hex(\openssl_random_pseudo_bytes($length));
        } else {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $uuid = substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
        }
        return $uuid;
    }
}
if (!function_exists('filter_emoji')) {
    function filter_emoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        $cleaned = strip_tags($str);
        return htmlspecialchars(($cleaned));
    }


}

if (!function_exists('convert_underline')) {
    function convert_underline($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $str);
        return $str;
    }
}
if (!function_exists('hump_to_line')) {

    /*
        * 驼峰转下划线
        */
    function hump_to_line($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }
}
if (!function_exists('convert_hump')) {

    function convert_hump(array $data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[convert_underline($key)] = convert_hump((array)$item);
            } else {
                $result[convert_underline($key)] = $item;
            }
        }
        return $result;
    }
}

if (!function_exists('page')) {
    /**
     * 分页查询助手.
     *
     * @param $query
     * @param $data
     *
     * @return QueryHelper
     */
    function page($query, $data): QueryHelper
    {
        return (new QueryHelper())->setQuery($query)->setData($data);
    }
}


if (!function_exists('cache')) {
    function cache(): CacheInterface
    {
        return ApplicationContext::getContainer()->get(CacheInterface::class);
    }
}


if (!function_exists('cache_has_set')) {
    function cache_has_set(string $key, $callback, $tll = 3600)
    {
        $data = cache()->get($key);
        if ($data || $data === false) {
            return $data;
        }
        if ($callback instanceof Closure){
            $data = call_user_func($callback);
        }else{
            $data = $callback;
        }
        if ($data === null) {
            p('设置空缓存防止穿透');
            cache()->set($key, false, 10);
        } else {
            cache()->set($key, $data, $tll);
        }
        return $data;
    }
}




if (!function_exists('event_dispatch')) {
    function event_dispatch(object $object)
    {
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($object);
        return true;
    }
}

if (!function_exists('array_filter_null')) {
    function array_filter_null($arr, $empty_array = false)
    {
        return array_filter($arr, function ($item) use ($empty_array) {
            if ($item === '' || $item === null || (is_array($item) && $empty_array && empty($item))) {
                return false;
            }
            return true;
        });
    }
}


if (!function_exists('cache_clear_prefix')) {
    function cache_clear_prefix($key)
    {
        /** @var CacheManager $manager */
        $manager = ApplicationContext::getContainer()->get(CacheManager::class);
        /** @var \Hyperf\Cache\Driver\RedisDriver $driver */
        $driver = $manager->getDriver();
        $driver->clearPrefix($key);
    }
}

if (!function_exists('get_millisecond')) {
    // 毫秒级时间戳
    function get_millisecond()
    {
        return round(microtime(true) * 1000);
    }
}
