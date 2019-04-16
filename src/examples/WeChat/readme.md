
# 第三方平台消息接收处理使用说明

### 1. 初始化

请查看 [控制器初始化](https://github.com/wujiangweiphp/weapp/blob/master/README.md)

### 2. 实例化

消息的接收及处理（thinkphp5为例） ：

```php
namespace app\wechat\controller;

use think\Request;
use weapp\WxMessage;
use WeChat\Wx3rdMessage;
use WeChat\WxCommonMessage;

class Message extends Base
{
    private $input ; // 构造函数已经接收过一遍了 其他地方再接收则会为空

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->input = $request->getInput();
    }

    /**
     * @todo: 获取小程序单个appid授权消息接收
     * @date: 2019/1/9
     * @return \think\response\Json
     */
    public function receiveMsg($appid)
    {
        $wx3rdMessageDeal = new Wx3rdMessage();  //第三方消息处理类
        $wxCommonMessageDeal = new WxCommonMessage(); //普通消息处理类
        $message = new WxMessage($this->wxComponentService,$this->input,$wx3rdMessageDeal,$wxCommonMessageDeal,$appid);
        $message->dealMsg();
    }

}
```

### 3. 设置必要的全局变量

素材上传路径 `tp5/application/extra/wechat.php`

```php
 'MEDIA_TEMP_PATH' => 'MEDIA_TEMP_PATH' => ROOT_PATH. 'runtime/temp/',
```

