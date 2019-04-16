<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 16:44
 */

namespace WeChat;


use weapp\Wx3rdMessageDeal;

class Wx3rdMessage extends Wx3rdMessageDeal
{

    /**
     * 获取ticket消息处理
     * @param  array $message_array 消息数组
     */
    public function componentVerifyTicketDeal($message_array = array())
    {
        die('success');
    }

    /**
     * 取消授权消息处理 (无须返回消息)
     * @param  array $message_array 消息数组
     * @throws
     */
    public function unauthorizedDeal($message_array = array())
    {
        //更改数据库授权状态为 --- 未授权
        die('success');
    }

    /**
     * 授权成功消息处理
     * @param  array $message_array 消息数组
     * @throws
     */
    public function authorizedDeal($message_array = array())
    {
        //更改数据库授权状态为 --- 已授权
        die('success');
    }

    /**
     * 更新授权消息处理
     * @param  array $message_array 消息数组
     */
    public function updateauthorizedDeal($message_array = array())
    {
        die('success');
    }

    /**
     * 其他消息处理
     * @param  array $message_array 消息数组
     */
    public function otherDeal($message_array = array())
    {
        // TODO: Implement otherDeal() method.
    }
}