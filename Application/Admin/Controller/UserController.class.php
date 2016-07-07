<?php
namespace Admin\Controller;

use Think\Controller;

class UserController extends BaseController
{
    /*每页显示数量*/
    private $pageSize = 5;

    public function index()
    {
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $user = M('user');// 实例化Data数据模型

        $count = $user->count();// 查询满足要求的总记录数
        $Page = new \Admin\Lib\Page($count, $this->pageSize);// 实例化分页类 传入总记录数和每页显示的记录数(5)
        $show = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $user->field('web_user.user_id,user,nickname,img,sex,web_power.name,web_user.create_time,last_login_time,last_login_ip,status')->join('LEFT JOIN web_power ON web_user.power_id = web_power.power_id')->order('user_id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

//        $list = $category->order('cate_id asc')->select();

//        var_dump($list);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {

            $obj = D('user');

            $data = $this->createData();

            $msg = uploadImg('pic');

            if ($msg == '0') {
                $this->error('上传失败！');

            } else if ($msg == '1') {
//                $this->error('请选择图片上传！');
            } else {
                // 图片上传成功
                $data['img'] = __ROOT__ . '/Uploads/' . $msg;
            }

            if ($obj->add($data)) {
                //添加数据成功
                $this->success("添加成功", U('user/index'));
            } else {
                //添加数据失败
                $this->error('操作失败！');
            }

        } else {
            $power = M('power');// 实例化Data数据模型
            $list = $power->order('power_id asc')->select();
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function edit($article_id = 0)
    {
        $obj = D('user');

        if (IS_POST) {
            $data = $this->createData();
            $data['user_id'] = $article_id;

            $msg = uploadImg('pic');

            if ($msg == '0') {
                $this->error('上传失败！');

            } else if ($msg == '1') {
//                    $this->error('请选择图片上传！');

            } else {
                // 图片上传成功
                $data['img'] = __ROOT__ . '/Uploads/' . $msg;

            }

            if ($obj->save($data)) {
                $this->success('操作成功', U('user/index'));
                return;
            } else {
                $this->error('操作失败');
                return;
            }
        } else {
            $type = M('type');// 实例化Data数据模型
            $list = $type->order('type_id asc')->select();

            $article_id = (int)$article_id;
            if (!$detail = $obj->find($article_id)) {
                $this->error('请选择要编辑的文章');
            }

            $this->assign('list', $list);
            $this->assign('detail', $detail);
            $this->display();
        }
    }

    public
    function del()
    {
        $user = D('user');
        $user_id = I('get.user_id');
        $page = I('get.page');//当前页数

        $result = $user->where('user_id =' . $user_id . '')->delete();


        if ($result) {

            $count = $user->count();// 查询满足要求的总记录数
            $pageNum = $count % $this->pageSize > 0 ? $count / $this->pageSize + 1 : $count / $this->pageSize;//总共多少页
            if ($page > $pageNum) {
                //当前页数大于总页数时，设置当前页为最后一页
                $page = $pageNum;
            }

            $this->success('删除成功', U('user/index?p=' . $page));
        } else {
            $this->error('删除失败');
        }
    }

    private
    function createData()
    {

        $data = array();
        $create_time = strtotime(date("Y-m-d H:i:s", time()));
        //账号
        $data['user'] = I('post.user');
        if ($data['user'] == null) {
            $this->error('账号不能为空');
            return;
        }

        //图片链接
//        $data['img'] = I('post.img');
        //密码
        $password = I('post.password');
        //确认密码
        $confirm_password = I('post.confirm_password');
        if ($password == null) {
            $this->error('请输入密码');
            return;
        }
        if ($password != $confirm_password) {
            $this->error('两次密码不一致');
            return;
        }
        $data['password'] = md5($password);

        //创建时间
        $data['create_time'] = $create_time;
        //性别
        $data['sex'] = I('post.sex');
        //昵称
        $data['username'] = I('post.username');
        //权限
        $data['power_id'] = I('post.power_id');
        //账号状态
        $data['status'] = I('post.status', '0');

        return $data;
    }


}