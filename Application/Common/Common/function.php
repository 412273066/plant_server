<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 2016/2/29
 * Time: 9:12
 *
 * $resCode 1成功 0失败
 *
 */
function createJson($resCode, $msg, $detailMsg, $list)
{
    $data = array('list' => $list, 'resCode' => $resCode, 'msg' => $msg, 'detailMsg' => $detailMsg);

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return $json;
}

/**
 * 上传一张图片
 * @param $formName 表单中file的name值
 * @return string 存储路径
 */

function uploadImg($formName)
{
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize = 3 * 1024 * 1024;// 设置附件上传大小
    $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->rootPath = './Uploads/'; // 设置附件上传根目录
    $upload->savePath = ''; // 设置附件上传（子）目录
    // 上传文件
    $info = $upload->uploadOne($_FILES[$formName]);
    if (!$info) {// 上传错误提示错误信息

        if ($upload->getError() == '没有文件被上传！') {
            //没有上传文件情况
            return '1';
        } else {
            //其他失败情况
            return '0';
        }
    } else {// 上传成功
        return $info['savepath'] . $info['savename'];
    }
}

/**
 * 验证手机号是否正确
 * @author 范鸿飞
 * @param INT $mobile
 */
function isMobile($mobile)
{
    if (!is_numeric($mobile)) {
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
}


?>