<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 17:16
 */

namespace WeChat;

use think\Cache;
use think\Config;
use think\Log;
use weapp\WxCommonMessageDeal;

class WxCommonMessage extends WxCommonMessageDeal
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
    public function textDeal($message_array = array())
    {
        $return_content = '';
        $content        = empty($message_array['Content']) ? (empty($message_array['Recognition']) ? '' : $message_array['Recognition']) : $message_array['Content'];
        if (empty($content)) {
            return false;
        }
        /********************************** 全网发布消息处理 ************************************/
        if ($message_array['FromUserName'] == 'gh_3c884a361561' || $message_array['FromUserName'] == 'gh_8dad206e9538') {
            $needle    = 'QUERY_AUTH_CODE:';
            $tmp_array = explode($needle, $content);
            //3、模拟粉丝发送文本消息给专用测试公众号，第三方平台方需在5秒内返回空串，表明暂时不回复，然后再立即使用客服消息接口发送消息回复粉丝
            if (count($tmp_array) > 1) {
                $auth_code              = $tmp_array[1]; //实际内容
                $component_access_token = $this->getWxCommpontService()->getComponentAccessToken();
                $auth_info              = $this->getWxCommpont()->getWxAuthInfo($component_access_token, $auth_code);
                $app_access_token       = $auth_info['authorization_info']['authorizer_access_token'];
                $reply_content          = $auth_code . "_from_api";
                echo '';
                fastcgi_finish_request();
                $data = array(
                    'content' => array(
                        'touser'  => $message_array['FromUserName'],
                        'msgtype' => 'text',
                        'text'    => array('content' => $reply_content),
                    )
                );
                $this->getWxCommpont()->replyKefuMessage($app_access_token, $data);
                return '';
            } else {
                //2、模拟粉丝发送文本消息给专用测试公众号
                if ($content == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
                    return array(
                        'content' => "TESTCOMPONENT_MSG_TYPE_TEXT_callback",
                        'type'    => 'text'
                    );
                }
            }
        }
        /********************************** 其他文本消息处理 ************************************/
        $ai_data        =  '关键词回复 处理... ' ;
        $return_content = "暂未找到相关内容，提示相关输入信息...";

        if (stripos($content, '客服') !== false) {
            $kefu_list = $this->getWxCommpontService()->getOnlineKFlist($this->getAppid());
            if (empty($kefu_list['kf_online_list'])) {
                $return_message = array(
                    'type'    => 'text',
                    'content' => array(
                        'touser'  => $message_array['FromUserName'],
                        'msgtype' => 'text',
                        'text'    => array('content' => '暂无客服在线，您可以留言'),
                    )
                );
                $this->getWxCommpontService()->replyKefuMessage($this->getAppid(), $return_message);
            } else {
                $return_message = array(
                    'type'    => 'text',
                    'content' => array(
                        'touser'  => $message_array['FromUserName'],
                        'msgtype' => 'text',
                        'text'    => array('content' => '正在为您转接客服，请稍后...'),
                    )
                );
                $this->getWxCommpontService()->replyKefuMessage($this->getAppid(), $return_message);
                $this->getWxCommpontService()->transCustomMessage($message_array);
            }
            return false;
        } elseif (!empty($ai_data['data'])) {
            $return_content = $ai_data['data']['content'];
            if (!empty($ai_data['data']['image'])) {
                $media_id = $this->getMediaId($ai_data['data']['image']);
                return array(
                    'is_custom' => 1,  //为客服消息回复  非客服消息可不传此参数
                    'type'      => 'image',
                    'content'   => array(
                        'touser'  => $message_array['FromUserName'],
                        'msgtype' => 'image',
                        'image'   => array('media_id' => $media_id),
                    )
                );
            }
        }

        return array(
            'is_custom' => 1,  //为客服消息回复  非客服消息可不传此参数
            'type'      => 'text',
            'content'   => array(
                'touser'  => $message_array['FromUserName'],
                'msgtype' => 'text',
                'text'    => array('content' => $return_content),
            )
        );
    }

    /**
     * @todo: 根据url获取临时素材media
     * @author： friker
     * @date: 2019/4/11
     * @param string $url
     * @return mixed|string
     */
    public function getMediaId($url = '')
    {
        if (empty($url)) {
            return '';
        }
        $appid    = $this->getAppid();
        $key      = 'OpenAuth:Media:' . $appid . '-' . $url;
        $media_id = Cache::get($key);
        if (!empty($media_id)) {
            return $media_id;
        }
        $path      = Config::get('wechat.MEDIA_TEMP_PATH');
        $current   = time();
        $real_path = $path . $current . '.png';
        file_put_contents($real_path, file_get_contents($url));
        $result = $this->getWxCommpontService()->uploadMedia($appid, $real_path);
        if (empty($result)) {
            return '';
        }
        @unlink($real_path);
        // 255600 = 24 * 3600 * 3 - 3600
        Cache::set($key, $result['media_id'], 255600);
        return $result['media_id'];
    }

    /**
     * 图片消息处理
     * @param  array $message_array 消息数组
     */
    public function imageDeal($message_array = array())
    {
        // TODO: Implement imageDeal() method.
    }

    /**
     * 音频消息处理
     * @param  array $message_array 消息数组
     */
    public function voiceDeal($message_array = array())
    {
        // TODO: Implement voiceDeal() method.
    }

    /**
     * 视频消息处理
     * @param  array $message_array 消息数组
     */
    public function videoDeal($message_array = array())
    {
        // TODO: Implement videoDeal() method.
    }

    /**
     * 小视频消息处理
     * @param  array $message_array 消息数组
     */
    public function shortvideoDeal($message_array = array())
    {
        // TODO: Implement shortvideoDeal() method.
    }

    /**
     * 地理位置消息处理
     * @param  array $message_array 消息数组
     */
    public function locationDeal($message_array = array())
    {
        // TODO: Implement locationDeal() method.
    }

    /**
     * 链接消息处理
     * @param  array $message_array 消息数组
     */
    public function linkDeal($message_array = array())
    {
        // TODO: Implement linkDeal() method.
    }

    /************************************************* 事件消息处理部分 **********************************************************/
    /**
     * 事件处理部分
     * @param  array $message_array 消息数组
     * @return
     */
    public function eventDeal($message_array = array())
    {
        /************************** 处理全网发布事件**********************************/
        if ($message_array['FromUserName'] == 'gh_3c884a361561' || $message_array['FromUserName'] == 'gh_8dad206e9538') {
            $event          = empty($message_array['Event']) ? $message_array['EventKey'] : $message_array['Event'];
            $return_content = $event . "from_callback";
            return array(
                'content' => $return_content,
                'type'    => 'text'
            );
        }

        /************************** 处理普通事件**********************************/

        switch ($message_array['Event']) {
            case "subscribe":
                // 关注事件消息处理
                return $this->eventSubscribeDeal($message_array);
                break;
            case "unsubscribe":
                // 取消关注事件消息处理
                return $this->eventUnsubscribeDeal($message_array);
                break;
            case "SCAN":
                // 扫描带参数二维码
                return $this->eventScanDeal($message_array);
                break;
            case "LOCATION":
                // 上报地理位置事件
                return $this->eventLocationDeal($message_array);
                break;
            case "CLICK":
                // 点击自定义菜单事件
                return $this->eventClickDeal($message_array);
                break;
            case "VIEW":
                // 点击菜单跳转链接事件推送
                return $this->eventViewDeal($message_array);
                break;
            case "weapp_audit_success":
                // 第三方小程序 发布审核成功通知
                return $this->eventWeappAuditSuccessDeal($message_array);
                break;
            case "weapp_audit_fail":
                // 第三方小程序 发布审核失败通知
                return $this->eventWeappAuditFailDeal($message_array);
                break;
            case 'user_enter_tempsession':
                //进入小程序客服 会话状态唤醒
                return $this->eventUserEnterTempsessionDeal($message_array);
                break;
            default :
                return $this->otherDeal($message_array);
                break;
        }
    }

    /**
     * 关注事件处理
     * @param  array $message_array 消息数组
     */
    public function eventSubscribeDeal($message_array = array())
    {
        // TODO: Implement eventSubscribeDeal() method.
    }

    /**
     * 取消关注事件处理
     * @param  array $message_array 消息数组
     */
    public function eventUnsubscribeDeal($message_array = array())
    {
        // TODO: Implement eventUnsubscribeDeal() method.
    }

    /**
     * 二维码扫描事件处理
     * @param  array $message_array 消息数组
     */
    public function eventScanDeal($message_array = array())
    {
        // TODO: Implement eventScanDeal() method.
    }

    /**
     * 上报地理位置事件处理
     * @param  array $message_array 消息数组
     */
    public function eventLocationDeal($message_array = array())
    {
        // TODO: Implement eventLocationDeal() method.
    }

    /**
     * 点击菜单事件处理
     * @param  array $message_array 消息数组
     */
    public function eventClickDeal($message_array = array())
    {
        // TODO: Implement eventClickDeal() method.
    }

    /**
     * 点击菜单跳转链接事件处理
     * @param  array $message_array 消息数组
     */
    public function eventViewDeal($message_array = array())
    {
        // TODO: Implement eventViewDeal() method.
    }

    /**
     * 其他消息处理
     * @param  array $message_array 消息数组
     * @return
     */
    public function otherDeal($message_array = array())
    {
        // TODO: Implement otherDeal() method.
        return false;
    }

    /**
     * 第三方小程序 发布审核成功通知 | 将审核通过的小程序发添加到发布队列
     * @param  array $message_array 消息数组
     * @return null
     * @throws
     */
    public function eventWeappAuditSuccessDeal($message_array = array())
    {
        return '';
    }

    /**
     * 第三方小程序 发布审核失败通知
     * @param  array $message_array 消息数组
     * @return null
     */
    public function eventWeappAuditFailDeal($message_array = array())
    {
        //
    }

    /**
     * 进入小程序客服 会话状态唤醒
     * @param  array $message_array 消息数组
     * @return null
     */
    public function eventUserEnterTempsessionDeal($message_array = array())
    {
        $return_content = "欢迎您的到来，您可以输入...";
        return array(
            'is_custom' => 1,  //为客服消息回复  非客服消息可不传此参数
            'type'      => 'text',
            'content'   => array(
                'touser'  => $message_array['FromUserName'],
                'msgtype' => 'text',
                'text'    => array('content' => $return_content),
            )
        );
    }
}