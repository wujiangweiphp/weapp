<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 16:55
 */

namespace weapp;


abstract class WxCommonMessageDeal extends WxMessageDeal
{
    /**
     * 文本消息处理
     * @param  array $message_array 消息数组
     * @return array | bool $message_array = array (
     *             'content' =>  返回内容
     *             'type'    =>  返回类型 如 'text'| 'image' | 'voice' 不传默认返回 'text'
     *             'encrypt_type' => 加密类型  不传默认返回 'aex'
     *          )
     *  如无消息返回 直接return false 就好
     */
    abstract public function textDeal($message_array = array());
    /**
     * 图片消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function imageDeal($message_array = array());
    /**
     * 音频消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function voiceDeal($message_array = array());
    /**
     * 视频消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function videoDeal($message_array = array());
    /**
     * 小视频消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function shortvideoDeal($message_array = array());
    /**
     * 地理位置消息处理
     * @param  array $message_array 消息数组  
     */
    abstract public function locationDeal($message_array = array());
    /**
     * 链接消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function linkDeal($message_array = array());
    /**
     * 其他消息处理
     * @param  array $message_array 消息数组
     */
    abstract public function otherDeal($message_array = array());

    /************************** 事件处理 *****************************/
    /**
     * 关注事件处理
     * @param  array $message_array 消息数组
     */
    abstract public function eventDeal($message_array = array());

    /**
     * @todo: 消息处理
     * @author： friker
     * @date: 2019/4/10
     * @param array $message_array 消息数组
     * @return mixed
     */
    public function dealMsg($message_array = array())
    {
        switch ($message_array['MsgType']) {
            case "text":
                // 文本消息处理
                return $this->textDeal($message_array);
                break;
            case "image":
                // 图片消息处理
                return $this->imageDeal($message_array);
                break;
            case "voice":
                // 音频消息处理
                return $this->voiceDeal($message_array);
                break;
            case "video":
                // 视频消息处理
                return $this->videoDeal($message_array);
                break;
                case "shortvideo":
                // 小视频消息处理
                return $this->shortvideoDeal($message_array);
                break;
                case "location":
                // 地理位置消息处理
                return $this->locationDeal($message_array);
                break;
            case "link":
                // 链接消息处理
                return $this->linkDeal($message_array);
                break;
            case "event":
                // 事件消息处理
                return $this->eventDeal($message_array);
                break;
            default:
                return $this->otherDeal($message_array);
                break;
        }
    }
}