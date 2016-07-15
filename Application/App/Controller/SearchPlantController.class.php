<?php
namespace App\Controller;

use Think\Controller;

class SearchPlantController extends Controller
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
            $keyword = $str['keyword'];
        }


//        echo(var_dump(I('post.')));
        if (!isset($keyword)) {
            $json = createJson(0, "请输入关键字!", "请输入要搜索的关键字!", null);
            echo($json);
            return;
        }


        $plant = M('plant');// 实例化Data数据模型
        $list = $plant->where('plant_name like  \'%' . $keyword . '%\'')->select();

        if (count($list) > 0) {

            $resCode = '1';
            $msg = '获取数据成功';
            $detailMsg = '获取搜索数据成功';

        } else {

            $resCode = '0';
            $msg = '暂无数据';
            $detailMsg = '数据库暂无数据';

        }
        $json = createJson($resCode, $msg, $detailMsg, $list);

        echo($json);
    }

}