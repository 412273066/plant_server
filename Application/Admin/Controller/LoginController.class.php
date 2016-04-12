<?php
namespace Admin\Controller;

use Think\Controller;

class LoginController extends BaseController
{
    public function index()
    {
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }

    public function dologin()
    {

        $user = I('post.user');
        $password = md5(I('post.password'));

        if (empty($user) || empty($password)) {
            $data['error'] = 1;
            $data['msg'] = "请输入帐号密码";
            $this->ajaxReturn($data);

        }
        $map = array();
        $map['user'] = $user;
        $map['status'] = 1;

        $admin = D('user');


        $adminInfo = $admin->where($map)->find();

        if ($adminInfo) {
             //注意回车空格
            if ($adminInfo['password'] != $password) {
                $data['error'] = 1;
                $data['msg'] = '帐号密码不正确';
                $this->ajaxReturn($data);
            }

            session('last_login_time', date('Y-m-d H:i:s', $adminInfo['last_login_time']));
            session('ip', $adminInfo['last_login_ip']);
            session('admin', $adminInfo);
            $data = array();
            $data['last_login_time'] = time();
            $data['last_login_ip'] = "127.0.0.1";
            $admin->where(array('id' => $adminInfo['user_id']))->save($data);
            $data1['error'] = 0;
            $data1['url'] = U('Index/index');
            $this->ajaxReturn($data1);

        } else {
            $data['error'] = 1;
            $data['msg'] = '帐号不存在或者被禁用';
            $this->ajaxReturn($data);

        }
    }

    public function dologout()
    {
        session('admin', null);
        redirect(U('Login/index'));
    }
}