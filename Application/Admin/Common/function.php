<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 2016/2/29
 * Time: 9:12
 */
function createJson($resCode, $msg, $detailMsg, $list)
{
    $data = array('list' => $list, 'resCode' => $resCode, 'msg' => $msg, 'detailMsg' => $detailMsg);

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return $json;
}


function uploadImg()
{
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize = 3 * 1024 * 1024;// 设置附件上传大小
    $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->rootPath = './Uploads/'; // 设置附件上传根目录
    $upload->savePath = ''; // 设置附件上传（子）目录
    // 上传文件
    $info = $upload->upload();
    if (!$info) {// 上传错误提示错误信息
        return $upload->getError();
    } else {// 上传成功
        return "1";
    }
}

?>