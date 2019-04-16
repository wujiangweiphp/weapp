<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 16:55
 */

namespace weapp;


abstract class Wx3rdMessageDeal extends WxMessageDeal
{
    /**
     * 获取ticket消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function componentVerifyTicketDeal($message_array = array());
    /**
     * 取消授权消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function unauthorizedDeal($message_array = array());
    /**
     * 授权成功消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function authorizedDeal($message_array = array());
    /**
     * 更新授权消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function updateauthorizedDeal($message_array = array());
    /**
     * 其他消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function otherDeal($message_array = array());

    /**
     * @todo: 消息处理
     * @author： friker
     * @date: 2019/4/10
     * @param $message_array array 消息数组
     * @return mixed
     */
    public function dealMsg($message_array = array())
    {
        switch ($message_array['InfoType']) {
            case "component_verify_ticket":
                // 每十分钟获取 ticket 处理
                return $this->componentVerifyTicketDeal($message_array);
                break;
            case "unauthorized":
                // 取消第三方授权处理
                return $this->unauthorizedDeal($message_array);
                break;
            case "authorized":
                // 授权成功处理
                return $this->authorizedDeal($message_array);
                break;
            case "updateauthorized":
                // 更新授权处理
                return $this->updateauthorizedDeal($message_array);
                break;
            default:
                return $this->otherDeal($message_array);
                break;
        }
    }
}