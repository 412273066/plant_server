<?php
namespace Admin\Controller;

use Think\Controller;

class ArticleController extends BaseController
{
    /*每页显示数量*/
    private $pageSize = 5;

    public function index()
    {
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $type = M('article');// 实例化Data数据模型

        $count = $type->count();// 查询满足要求的总记录数
        $Page = new \Admin\Lib\Page($count, $this->pageSize);// 实例化分页类 传入总记录数和每页显示的记录数(5)
        $show = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $type->field('article_id,title,type_name,summary,web_article.img,web_article.create_time,web_article.user_id')->join('LEFT JOIN web_type ON web_article.type_id = web_type.type_id')->order('article_id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

//        $list = $category->order('cate_id asc')->select();

//        var_dump($list);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {

            $obj = D('article');

            $data = $this->createData();

            $msg = uploadImg('pic');

            if ($msg == '0') {
                $this->error('上传失败！');

            } else if ($msg == '1') {
                $this->error('请选择图片上传！');
            } else {
                // 图片上传成功
                $data['img'] = __ROOT__ . '/Uploads/' . $msg;
            }

            if ($obj->add($data)) {
                //添加数据成功
                $this->success("添加成功", U('article/index'));
            } else {
                //添加数据失败
                $this->error('操作失败！');
            }

        } else {
            $category = M('type');// 实例化Data数据模型
            $list = $category->order('type_id asc')->select();
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function edit($article_id = 0)
    {
        $obj = D('article');

        if (IS_POST) {
            $data = $this->createData();
            $data['article_id'] = $article_id;

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
                $this->success('操作成功', U('article/index'));
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
        $type = D('article');
        $article_id = I('get.article_id');
        $page = I('get.page');//当前页数

        $result = $type->where('article_id =' . $article_id . '')->delete();


        if ($result) {

            $count = $type->count();// 查询满足要求的总记录数
            $pageNum = $count % $this->pageSize > 0 ? $count / $this->pageSize + 1 : $count / $this->pageSize;//总共多少页
            if ($page > $pageNum) {
                //当前页数大于总页数时，设置当前页为最后一页
                $page = $pageNum;
            }

            $this->success('删除成功', U('article/index?p=' . $page));
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
        $data['title'] = I('post.article_title');
        if ($data['title'] == null) {
            $this->error('文章标题不能为空');
            return;
        }
        //创建时间
        $data['create_time'] = $create_time;
        //图片链接
//        $data['img'] = I('post.img');
        //管理员id
        $data['user_id'] = I('session.admin')['user_id'];
//摘要
        $data['summary'] = I('post.article_summary');
//内容 默认标签htmlspecialchars转义，须对标签解码才能显示
        $data['content'] = htmlspecialchars_decode(I('post.article_content'));
//作者
        $data['author'] = I('post.article_author');
//文章类型
        $data['type_id'] = I('post.type_id');
//作者
        $data['author'] = I('post.article_author');
//关键字
        $data['keywords'] = I('post.article_keywords');


        return $data;
    }


}