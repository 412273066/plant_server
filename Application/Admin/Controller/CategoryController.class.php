<?php
namespace Admin\Controller;

use Think\Controller;

class CategoryController extends BaseController
{
    private $create_fields = array('cate_name', 'img', 'user_id', 'create_time');
    /*每页显示数量*/
    private $pageSize = 5;

    public function index()
    {
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $category = M('category');// 实例化Data数据模型

        $count = $category->count();// 查询满足要求的总记录数
        $Page = new \Admin\Lib\Page($count, $this->pageSize);// 实例化分页类 传入总记录数和每页显示的记录数(5)
        $show = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $category->order('cate_id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

//        $list = $category->order('cate_id asc')->select();

//        var_dump($list);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {

            $obj = D('category');

            $data = $this->createData();

            $msg = uploadImg('pic');

            if ($msg == '0') {
                $this->error('上传失败！');

            } else if ($msg == '1') {
                //没有上传文件情况，保存url

            } else {
                // 图片上传成功
                $data['img'] = __ROOT__ . '/Uploads/' . $msg;

            }

            if ($obj->add($data)) {
                //添加数据成功
                $this->success("添加成功", U('Category/index'));
            } else {
                //添加数据失败
                $this->error('操作失败！');
            }

        } else {
            $this->display();
        }
    }

    public function edit($cate_id = 0)
    {
        $obj = D('category');

        if ($cate_id = (int)$cate_id) {
            if (!$detail = $obj->find($cate_id)) {
                $this->error('请选择要编辑的种类');
            }
            if (IS_POST) {
                $data = $this->editCheck();
                $data['cate_id'] = $cate_id;

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
                    $this->success('操作成功', U('Category/index'));
                    return;
                } else {
                    $this->error('操作失败');
                    return;
                }
            } else {
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->error('请选择要编辑的种类');
        }
    }

    public function del()
    {
        $category = D('category');
        $cate_id = I('get.cate_id');
        $page = I('get.page');//当前页数

        $result = $category->where('cate_id =' . $cate_id . '')->delete();


        if ($result) {

            $count = $category->count();// 查询满足要求的总记录数
            $pageNum = $count % $this->pageSize > 0 ? $count / $this->pageSize + 1 : $count / $this->pageSize;//总共多少页
            if ($page > $pageNum) {
                //当前页数大于总页数时，设置当前页为最后一页
                $page = $pageNum;
            }

            $this->success('删除成功', U('Category/index?p=' . $page));
        } else {
            $this->error('删除失败');
        }
    }

    private function createData()
    {

        $param = I('post.');
        $data = $this->checkFields($param["data"], $this->create_fields);
        $create_time = strtotime(date("Y-m-d H:i:s", time()));
        //类型的名字
        $data['cate_name'] = I('post.cate_name');
        if ($data['cate_name'] == null) {
            $this->error('类型名字不能为空');
            return;
        }
        //创建时间
        $data['create_time'] = $create_time;
        //图片链接
        $data['img'] = I('post.img');
        //管理员id
        $data['user_id'] = I('session.admin')['user_id'];

        return $data;
    }

    protected function checkFields($data = array(), $fields = array())
    {
        foreach ($data as $k => $val) {
            if (!in_array($k, $fields)) {
                unset($data[$k]);
            }
        }
        return $data;
    }

    private function editCheck()
    {
        $create_time = strtotime(date("Y-m-d H:i:s", time()));

        $data = array();

        $data['cate_name'] = I('post.cate_name');
        if ($data['cate_name'] == null) {
            $this->error('名字不能为空');
            return;
        }
        $data['create_time'] = $create_time;
//        $data['img'] = I('post.pic');
        $data['user_id'] = I('session.admin')['user_id'];

        return $data;
    }


}