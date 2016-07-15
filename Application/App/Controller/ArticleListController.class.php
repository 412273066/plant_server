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
            $type_id = $str['typeId'];
            $page = $str['page'];
            $size = $str['size'];
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
        if (!isset($type_id)) {
            //-1000则返回全部数据,默认返回全部数据
            $type_id = -1000;
        }

        $offset = $size * ($page - 1);

        $Model = new ArticleModel(); // 实例化一个model对象 没有对应任何数据表
        //没有提交type_id则返回全部文章
        if ($type_id == -1000) {
            $list = $Model->query("SELECT article_id,title,summary,content,web_article.img,web_article.type_id,web_article.create_time,type_name FROM web_article JOIN web_type ON web_article.type_id= web_type.type_id ORDER BY create_time DESC LIMIT " . $offset . "," . $size);
        } else {
            $list = $Model->query("SELECT article_id,title,summary,content,web_article.img,web_article.type_id,web_article.create_time,type_name FROM web_article JOIN web_type ON web_article.type_id= web_type.type_id WHERE web_article.type_id=" . $type_id . " ORDER BY create_time DESC LIMIT " . $offset . "," . $size);
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