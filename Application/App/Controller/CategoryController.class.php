<?php
namespace App\Controller;

use Think\Controller;

class CategoryController extends Controller
{
    public function index()
    {

        if (IS_POST) {
            $requestJson = $_POST['json'];  // 获取post变量
//            $requestJson = I('json',"","strip_tags");  // 必须加strip_tags获取post变量


            $str = json_decode($requestJson, true);


            if (json_last_error() != 0) {
                echo("提交数据格式出错!");
            } else {
                $page = $str['page'];
                //var_dump($str);
//                echo($page . '<br/>');
            }


        } else {
            echo('请用post方法请求<br>');
            $requestJson = $_GET['json'];//获取get变量

            $str = json_decode($requestJson, true);


            if (json_last_error() != 0) {
                echo("提交数据格式出错!");
            } else {
                $page = $str['page'];
                //var_dump($str);
//                echo($page . '<br/>');
            }
            return;
        }


        $category = M('category');// 实例化Data数据模型
        $list = $category->page($page, 6)->select();

        if (count($list) > 0) {

            $resCode = '1';
            $msg = '获取数据成功';
            $detailMsg = '获取分类数据成功';

        } else {

            $resCode = '0';
            $msg = '暂无数据';
            $detailMsg = '数据库暂无数据';

        }
        $data = array('list' => $list, 'resCode' => $resCode, 'msg' => $msg, 'detailMsg' => $detailMsg);

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo($json);
    }

}