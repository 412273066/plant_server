<?php
namespace App\Controller;

use App\Model\ArticleModel;
use Think\Controller;

class ArticleListController extends Controller
{
    public function index()
    {

        if (IS_POST) {
            $requestJson = $_POST['json'];  // 获取post变量
//            $requestJson = I('json',"","strip_tags");  // 必须加strip_tags获取post变量


            $str = json_decode($requestJson, true);


            if (json_last_error() != 0) {
                $json = createJson(0, "提交数据格式出错!", "提交数据必须json格式!", null);

                echo($json);
                return;
            } else {
                $type_id = $str['typeId'];
                $page = $str['page'];
                $size = $str['size'];

            }


        } else {
            // echo('请用post方法请求<br>');
            $requestJson = $_GET['json'];//获取get变量

            $str = json_decode($requestJson, true);


            if (json_last_error() != 0) {
                $json = createJson(0, "提交数据格式出错!", "提交数据必须json格式!", null);

                echo($json);
                return;
            } else {
                $type_id = $str['typeId'];
                $page = $str['page'];
                $size = $str['size'];
            }
//            return;
        }

//        echo(var_dump(I('post.')));


        if (!isset($page)) {
            $json = createJson(0, "请输入页码!", "请输入页码!", null);
            echo($json);
            return;
        }
        if (!isset($size)) {
            //默认一页8个数据
            $size = 8;
        }

        $Model = new ArticleModel(); // 实例化一个model对象 没有对应任何数据表
        if (!isset($type_id)) {

            $list = $Model->query("select title,summary,content,img,type_id,create_time from web_article ORDER BY create_time DESC");

        }else{
            $list = $Model->query("select title,summary,content,img,type_id,create_time from web_article where type_id=" . $type_id." ORDER BY create_time DESC");
        }

//        var_dump($list);

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