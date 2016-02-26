<?php
namespace App\Controller;

use Think\Controller;

class BannerController extends Controller
{
    public function index()
    {
        $Banner = M('Banner');// 实例化Data数据模型
        $list = $Banner->limit(5)->select();

        if (count($list) > 0) {

            $resCode = '1';
            $msg = '获取数据成功';
            $detailMsg = '获取广告栏数据成功';

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