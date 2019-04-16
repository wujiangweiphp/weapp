<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 18:00
 */

namespace WeChat;

use weapp\BaseCache;
use think\Cache;

class WxCache extends BaseCache
{

    public function setCache($cacheName, $cacheValue, $expireIn)
    {
        $expireIn = $expireIn < 0 ? 0 : $expireIn;
        $cacheName = "OpenAuth:".$cacheName;
        Cache::set($cacheName, $cacheValue, $expireIn);
    }

    public function getCache($cacheName)
    {
        $cacheName = "OpenAuth:".$cacheName;
        $data = Cache::get($cacheName);
        return $data;
    }

    public function removeCache($cacheName)
    {
        $cacheName = "OpenAuth:".$cacheName;
        Cache::rm($cacheName);
    }
}