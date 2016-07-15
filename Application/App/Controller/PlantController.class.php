<?php
namespace App\Controller;

use Think\Controller;

class PlantController extends Controller
{
    public function index()
    {

        if (IS_POST) {
            $requestJson = $_POST['json'];  // 获取post变量
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
            $cate_id = $str['categoryId'];
            $page = $str['page'];
            $size = $str['size'];

        }


//        echo(var_dump(I('post.')));
        if (!isset($cate_id)) {
            $json = createJson(0, "请输入类别!", "请输入植物类别!", null);
            echo($json);
            return;
        }

        if (!isset($page)) {
            $json = createJson(0, "请输入页码!", "请输入页码!", null);
            echo($json);
            return;
        }
        if (!isset($size)) {
            //默认一页5个数据
            $size = 5;
        }


        $plant = M('plant');// 实例化Data数据模型
        $list = $plant->where('cate_id=' . $cate_id)->page($page, $size)->select();

        if (count($list) > 0) {

            $resCode = '1';
            $msg = '获取数据成功';
            $detailMsg = '获取分类数据成功';

        } else {

            $resCode = '0';
            $msg = '暂无数据';
            $detailMsg = '数据库暂无数据';

        }
        $json = createJson($resCode, $msg, $detailMsg, $list);

        echo($json);
    }

}