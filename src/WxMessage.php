<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 16:15
 */

namespace weapp;

class WxMessage
{
    /**
     * @var array 消息解密数组
     */
    private $msgArr;
    /**
     * @var array 返回消息数组
     */
    private $returnMsgArr;
    /**
     * @var string  原生消息类型
     */
    private $msgRaw;

    /**
     * @var Wx3rdMessageDeal
     */
    private $wx3rdMessageDeal;

    /**
     * @var WxCommonMessageDeal
     */
    private $wxCommonMessageDeal;

    /**
     * @var WxComponentService
     */
    private $wxComponentService;

    /**
     * @var string
     */
    private $appid;

    /**
     * WxMessage constructor.
     * @param WxComponentService $wxComponentService
     * @param $msg_raw
     * @param Wx3rdMessageDeal $wx3rdMessageDeal 第三方消息处理
     * @param WxCommonMessageDeal $wxCommonMessageDeal 普通消息处理
     * @param string $appid 来自哪个小程序的appid
     */
    public function __construct(
        WxComponentService $wxComponentService,
        $msg_raw,
        Wx3rdMessageDeal $wx3rdMessageDeal,
        WxCommonMessageDeal $wxCommonMessageDeal,
        $appid = ''
    ) {
        $this->msgRaw              = $msg_raw;
        $this->msgArr              = $wxComponentService->onComponentEventNotify($msg_raw);
        $this->wxComponentService  = $wxComponentService;
        $this->wx3rdMessageDeal    = $wx3rdMessageDeal;
        $this->wxCommonMessageDeal = $wxCommonMessageDeal;
        $this->wx3rdMessageDeal->setWxCommpontService($wxComponentService);  // 设置以便外部可以访问
        $this->wxCommonMessageDeal->setWxCommpontService($wxComponentService); //设置 以便外部可以访问
        $this->wx3rdMessageDeal->setAppid($appid);  // 设置以便外部可以访问
        $this->wxCommonMessageDeal->setAppid($appid);  // 设置以便外部可以访问
        $this->appid = $appid;
    }

    /**
     * @todo: 获取消息数组
     * @author： friker
     * @date: 2019/4/10
     * @return array
     */
    public function getMsgArr()
    {
        return $this->msgArr;
    }

    /**
     * @todo: 获取原生消息
     * @author： friker
     * @date: 2019/4/10
     * @return string
     */
    public function getMsgRaw()
    {
        return $this->msgRaw;
    }

    /**
     * @todo: 第三方 | 普通消息 处理类
     * @author： friker
     * @date: 2019/4/10
     * @return WxCommonMessageDeal | Wx3rdMessageDeal
     */
    public function getMsgDealClass()
    {
        return isset($this->msgArr['InfoType']) ? $this->wx3rdMessageDeal : $this->wxCommonMessageDeal;
    }


    /**
     * @todo: 消息处理
     * @author： friker
     * @date: 2019/4/10
     * @return mixed
     */
    public function dealMsg()
    {
        $message_array = $this->getMsgArr();
        $this->wxComponentService->log(print_r($message_array, true));
        if (is_array($message_array)) {
            $return_msg = $this->getMsgDealClass()->dealMsg($message_array);
            return $this->replyMsg($return_msg);
        }
        return false;
    }

    /**
     * @todo: 消息回复处理
     * @author： friker
     * @date: 2019/4/10
     * @param array $return_msg
     * @return bool
     */
    public function replyMsg($return_msg = array())
    {
        if (empty($return_msg)) {
            return false;
        }
        if (empty($return_msg['is_custom'])) {
            return $this->wxComponentService->reply3rdMessage($this->getMsgArr(), $return_msg);
        }
        return $this->wxComponentService->replyKefuMessage($this->appid, $return_msg);
    }

}