<?php
namespace App\Controller;

use Think\Controller;

class UserController extends Controller
{
    public function register()
    {

        if (IS_POST) {
            $requestJson = $_POST['json'];
            // 获取post变量
//            $requestJson = I('json',"","strip_tags");  // 必须加strip_tags获取post变量
        } else {
            // echo('请用post方法请求<br>');
            $requestJson = $_GET['json'];//获取get变量
        }

        $str = json_decode($requestJson, true);

        if (json_last_error() != 0) {
            $json = createJson(0, "提交数据格式出错!", "提交数据必须json格式!", null);

            echo($json);

            return;
        } else {
            $msg = $this->checkData($str);
            $data = $this->createData($str);
        }


        if (!empty($msg)) {
            $json = createJson(0, $msg, "注册失败!" . $msg, null);
            echo($json);
            return;
        }


        $obj = D('user');// 实例化Data数据模型
        if ($obj->add($data)) {
            $resCode = '1';
            $msg = '恭喜你，注册成功';
            $detailMsg = '恭喜你，注册成功';
        } else {
            $resCode = '0';
            $msg = '抱歉，注册失败';
            $detailMsg = '抱歉，注册失败';
        }
        $json = createJson($resCode, $msg, $detailMsg, null);

        echo($json);
    }

    public function login()
    {
        if (IS_POST) {
            $requestJson = $_POST['json'];  // 获取post变量
        } else {
            // echo('请用post方法请求<br>');
            $requestJson = $_GET['json'];//获取get变量
        }
//      $requestJson = I('json',"","strip_tags");  // 必须加strip_tags获取post变量

        $str = json_decode($requestJson, true);

        if (json_last_error() != 0) {
            $json = createJson(0, "提交数据格式出错!", "提交数据必须json格式!", null);

            echo($json);

            return;
        } else {
            $data = array();
            //账号
            $data['user'] = $str['user'];
            //客户端权限
            $data['_string'] = 'web_user.power_id=2 OR web_user.power_id=3';
            //账户状态
            $data['status'] = 1;
            //密码
            $password = md5($str['password']);
        }

        if (empty($data['user'])) {
            $json = createJson(0, '请输入账号', "登录失败!，请输入账号", null);
            echo($json);
            return;
        }
        if (empty($password)) {
            $json = createJson(0, '请输入密码', "登录失败!，请输入密码", null);
            echo($json);
            return;
        }

        $user = D('user');

        $userInfo = $user->join('LEFT JOIN web_power ON web_user.power_id = web_power.power_id')->where($data)->field('user,password,nickname,sex,img,name')->find();

        if ($userInfo) {
            //注意密码最后包含回车空格
            if ($userInfo['password'] != $password) {
                $json = createJson(0, '密码不正确', "登录失败!密码不正确。", null);
                echo($json);
                return;
            }

            $data['last_login_time'] = time();
//          $data['last_login_ip'] = "127.0.0.1";
            $user->where(array('user_id' => $userInfo['user_id']))->save($data);
            $json = createJson(1, '登录成功。', '登录成功。', $userInfo);
            echo($json);

        } else {
            $json = createJson(0, '账号不存在或被禁用', "登录失败!账号不存在或被禁用。", null);
            echo($json);
            return;

        }

    }


    private function checkData($str)
    {
        if (!isset($str['user'])) {
            $msg = '请输入用户名(手机号)!';
        }

        if (!isMobile($str['user'])) {
            $msg = '请输入手机号码作为用户名';
        }
        if (!isset($str['password'])) {
            $msg = '请输入密码!';
        }

        $length = strlen($str['password']);
        if ($length < 6) {
            $msg = '请输入不小于6位密码!';
        }

        if (!isset($str['nickname'])) {
            $msg = '请输入昵称!';
        }
//        if (!isset($data['captcha'])) {
//        $msg = '请输入验证码!';
//        }
        if ($str['password'] != $str['com_password']) {
            $msg = '密码不一致!';
        }

        $map = array();
        $map['user'] = $str['user'];
        $user = M('user');

        $userInfo = $user->where($map)->find();

        if ($userInfo) {
            $msg = '账号已存在!';
        }

        return $msg;
    }

    private function createData($str)
    {
        $data = array();
        //账号
        $data['user'] = $str['user'];
        //密码
        $data['password'] = md5($str['password']);
        //确认密码
        $data['com_password'] = md5($str['com_password']);
        //昵称
        $data['nickname'] = $str['nickname'];
        //验证码
        $data['captcha'] = $str['captcha'];
        //注册时间
        $data['create_time'] = strtotime(date("Y-m-d H:i:s", time()));
        //角色
        $data['power_id'] = 2;
        //是否禁用
        $data['status'] = 1;

        return $data;

    }

}