<?php
namespace Admin\Controller;

use Think\Controller;

class PowerController extends BaseController
{
    /*每页显示数量*/
    private $pageSize = 10;

    public function index()
    {
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $power = M('power');// 实例化Data数据模型

        $count = $power->count();// 查询满足要求的总记录数
        $Page = new \Admin\Lib\Page($count, $this->pageSize);// 实例化分页类 传入总记录数和每页显示的记录数(5)
        $show = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $power->order('power_id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

//        $list = $category->order('cate_id asc')->select();

//        var_dump($list);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {

            $obj = D('power');

            $data = $this->createData();

            if ($obj->add($data)) {
                //添加数据成功
                $this->success("添加成功", U('power/index'));
            } else {
                //添加数据失败
                $this->error('操作失败！');
            }

        } else {
            $this->display();
        }
    }

    public function edit($power_id = 0)
    {
        $obj = D('power');

        if (IS_POST) {
            $data = $this->createData();
            $data['power_id'] = $power_id;

            if ($obj->save($data)) {
                $this->success('操作成功', U('power/index'));
                return;
            } else {
                $this->error('操作失败');

                return;
            }
        } else {

            $power_id = (int)$power_id;
            if (!$detail = $obj->find($power_id)) {
                $this->error('请选择要编辑的权限');
            }

            $this->assign('detail', $detail);
            $this->display();
        }
    }

    public
    function del()
    {
        $power = D('power');
        $power_id = I('get.power_id');
        $page = I('get.page');//当前页数

        $result = $power->where('power_id =' . $power_id . '')->delete();


        if ($result) {

            $count = $power->count();// 查询满足要求的总记录数
            $pageNum = $count % $this->pageSize > 0 ? $count / $this->pageSize + 1 : $count / $this->pageSize;//总共多少页
            if ($page > $pageNum) {
                //当前页数大于总页数时，设置当前页为最后一页
                $page = $pageNum;
            }

            $this->success('删除成功', U('power/index?p=' . $page));
        } else {
            $this->error('删除失败');
        }
    }

    private
    function createData()
    {

        $data = array();
        $create_time = strtotime(date("Y-m-d H:i:s", time()));
        //标题
        $data['name'] = I('post.power_name');
        if ($data['name'] == null) {
            $this->error('权限名称不能为空');
            return;
        }
        //创建时间
        $data['create_time'] = $create_time;
        //图片链接
//        $data['img'] = I('post.img');
        //管理员id
        $data['user_id'] = I('session.admin')['user_id'];
//摘要
        $data['remark'] = I('post.remark');


        return $data;
    }


}