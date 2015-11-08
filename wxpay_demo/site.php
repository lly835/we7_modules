<?php
/**
 * 微信支付例子模块微站定义
 *
 * @author null
 * @url https://github.com/lly835/we7_modules
 */
defined('IN_IA') or exit('Access Denied');

class Wxpay_demoModuleSite extends WeModuleSite {

	public function doMobilePay() {
        //构造支付请求中的参数
        $params = array(
            'tid' => '1234567894',      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'ordersn' => '495959556',  //收银台中显示的订单号
            'title' => '充值',          //收银台中显示的标题
            'fee' => 0.01,      //收银台中显示需要支付的金额,只能大于 0
            //'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
        );
        //调用pay方法
        $this->pay($params);
	}

    /**
     * 支付后触发这个方法
     * @param $params
     */
    public function payResult($params) {

        //一些业务代码
        //根据参数params中的result来判断支付是否成功
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            //此处会处理一些支付成功的业务代码

            pdo_update('order', array('status' => 1), array('order_id' => $params['tid']));
        }
        //因为支付完成通知有两种方式 notify，return,notify为后台通知,return为前台通知，需要给用户展示提示信息
        //return做为通知是不稳定的，用户很可能直接关闭页面，所以状态变更以notify为准
        //如果消息是用户直接返回（非通知），则提示一个付款成功
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                message('支付成功！', '../../app/' . url('mc/home'), 'success');
            } else {
                message('支付失败！', '../../app/' . url('mc/home'), 'error');
            }
        }
    }


}