<?php
namespace Admin\Controller;

use Think\Controller;

class PlantController extends BaseController
{

    private $create_fields = array('plant_name', 'type', 'info', 'feature', 'habit', 'use', 'cate_id', 'img');
    /*每页显示数量*/
    private $pageSize = 5;

    public function index()
    {
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $plant = M('plant');// 实例化Data数据模型

        $count = $plant->count();// 查询满足要求的总记录数
        $Page = new \Admin\Lib\Page($count, $this->pageSize);// 实例化分页类 传入总记录数和每页显示的记录数(5)
        $show = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        //join操作对同名字段有冲突，通过修改字段名解决
        $sql = "select *,web_plant.img AS plant_img ,web_plant.create_time AS plant_create_time ,web_plant.user_id AS plant_user_id from web_plant JOIN web_category ON web_plant.cate_id = web_category.cate_id ORDER BY web_plant.plant_id ASC limit " . $Page->firstRow . ',' . $Page->listRows;
        $list = $plant->query($sql);

//        var_dump($list);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {

        $category = M('category');// 实例化Data数据模型
        $list = $category->order('cate_id asc')->select();


        if (IS_POST) {

            $obj = D('plant');

            $data = $this->createData();

            $msg = uploadImg('img');

            if ($msg == '0') {
                $this->error('上传失败！');

            } else if ($msg == '1') {
                $this->error('请选择图片上传！');

            } else {
                // 图片上传成功
                $data['img'] = __ROOT__ . '/Uploads/' . $msg;

            }

            if ($obj->add($data)) {
                //添加成功跳到最后一页
                $count = $obj->count();// 查询满足要求的总记录数
                $last_page = $count % $this->pageSize > 0 ? $count / $this->pageSize + 1 : $count / $this->pageSize;//总共多少页

                //添加数据成功
                $this->success('添加成功', U('Plant/index?p=' . $last_page));
            } else {
                //添加数据失败
                $this->error('操作失败！');
            }

        } else {
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function del()
    {
        $plant = D('plant');
        $plant_id = I('get.plant_id');
        $page = I('get.page');//当前页数

        $result = $plant->where('plant_id =' . $plant_id . '')->delete();


        if ($result) {

            $count = $plant->count();// 查询满足要求的总记录数
            $pageNum = $count % $this->pageSize > 0 ? $count / $this->pageSize + 1 : $count / $this->pageSize;//总共多少页
            if ($page > $pageNum) {
                //当前页数大于总页数时，设置当前页为最后一页
                $page = $pageNum;
            }

            $this->success('删除成功', U('Plant/index?p=' . $page));
        } else {
            $this->error('删除失败');
        }
    }

    private function createData()
    {

        $data = array();

        //植物名称
        $data['plant_name'] = I('post.plant_name');
        if ($data['plant_name'] == null) {
            $this->error('植物名称不能为空');
            return;
        }
        //创建时间
        $create_time = strtotime(date("Y-m-d H:i:s", time()));
        $data['create_time'] = $create_time;
        //图片链接
        $data['img'] = I('post.img');
        //管理员id
        $data['user_id'] = I('session.admin')['user_id'];
        //植物科属
        $data['type'] = I('post.plant_type');
        //植物介绍
        $data['info'] = I('post.plant_info');
        //特征形态
        $data['feature'] = I('post.plant_feature');
        //生长习性
        $data['habit'] = I('post.plant_habit');
        //主要用途
        $data['use'] = I('post.plant_use');
        //植物分类
        $data['cate_id'] = I('post.cate_id');


        return $data;
    }

}