<?php
namespace App\Controller;

use Think\Controller;

class ArticleTypeController extends Controller
{
    public function index()
    {
        $maxSize = 8;

        $Type = M('Type');// 实例化Data数据模型
        $list = $Type->limit($maxSize)->select();

        if (count($list) > 0) {

            $resCode = '1';
            $msg = '获取数据成功';
            $detailMsg = '获取文章类别数据成功';

        } else {

            $resCode = '0';
            $msg = '暂无数据';
            $detailMsg = '数据库暂无数据';

        }

        $json = createJson($resCode, $msg, $detailMsg, $list);

        echo($json);
    }

}