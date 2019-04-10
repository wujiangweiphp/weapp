# 微信|微信公众号|微信小程序|第三方平台使用库

微信开放平台SDK， 代小程序实现SDK，代公众号实现SDK，微信第三方开放平台SDK

### 1.使用前提

> php >= 5.5

### 2. 添加缓存抽象类

> 这里我们以 thinkphp5 举例

创建第三方库 `tp5/extend/WeChat/WxCache.php`

```php 

<?php
namespace WeChat;

use weapp\BaseCache;
use think\Cache;

class WxCache extends BaseCache
{

    public function setCache($cacheName, $cacheValue, $expireIn)
    {
        $expireIn = $expireIn < 0 ? 0 : $expireIn; //默认永不过期是 -1 但是redis 好像是 0
        $cacheName = "Trd3Auth:".$cacheName;
        Cache::set($cacheName, $cacheValue, $expireIn);
    }

    public function getCache($cacheName)
    {
        $cacheName = "Trd3Auth:".$cacheName;
        $data = Cache::get($cacheName);
        return $data;
    }

    public function removeCache($cacheName)
    {
        $cacheName = "Trd3Auth:".$cacheName;
        Cache::rm($cacheName);
    }
}

```

### 3. 控制器调用

> 将基础初始化放入根控制器即可

```php
class BaseController extends Controller
{
	public function __construct()
	{
	        $config                   = array(
	            'component_appid'     => COMPONENT_APPID,     //需要你自定义第三方常量
	            'component_appsecret' => COMPONENT_APPSECRET, //需要你自定义第三方常量
	            'encodingAesKey'      => ENCODING_AES_KEY,    //需要你自定义第三方常量
	            'token'               => TOKEN,               //需要你自定义第三方常量
	        );
	        $wxCache                  = new WxCache(); //缓存类
	        $this->wxComponentService = new WxComponentService($config, $wxCache); //服务类
	        $this->wxComponent        = $this->wxComponentService->getWxComponent();//组件类
	       
	        $func = function ($log) {
	            Log::write($log);
	        };
	        $this->wxComponent->debug = true;
	        $this->wxComponent->setLogcallback($func);
	}
}
```









