<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/11
 * Time: 9:53
 */

namespace weapp;


abstract class WxMessageDeal
{
    /**
     * @var \weapp\WxComponentService $wxComponentService
     */
    private $wxComponentService;

    /**
     * @var string
     */
    private $appid;

    /**
     * @todo: 返回当前appid
     * @author： friker
     * @date: 2019/4/11
     * @return string
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * @todo: 设置appid
     * @author： friker
     * @date: 2019/4/11
     * @param string $appid
     */
    public function setAppid($appid = '')
    {
        $this->appid = $appid;
    }
    
    
    /**
     * @todo: 消息处理
     * @author： friker
     * @date: 2019/4/10
     * @param array $message_array 消息数组
     * @return mixed
     */
    abstract public function dealMsg($message_array = array());

    /**
     * @todo: 获取微信组件类
     * @author： friker
     * @date: 2019/4/11
     * @return WxComponent
     */
    public function getWxCommpont()
    {
        return $this->wxComponentService->getWxComponent();
    }

    /**
     * @todo: 设置微信组件服务类
     * @author： friker
     * @date: 2019/4/11
     * @param WxComponentService $wxComponentService
     */
    public function setWxCommpontService(WxComponentService $wxComponentService)
    {
        $this->wxComponentService = $wxComponentService;
    }

    /**
     * @todo: 获取微信组件类服务类
     * @author： friker
     * @date: 2019/4/11
     * @return WxComponentService
     */
    public function getWxCommpontService()
    {
        return $this->wxComponentService;
    }
}