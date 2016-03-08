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


?>