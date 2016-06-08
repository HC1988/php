<?php
/**
 * Created by PhpStorm.
 * Author: linwang5
 * Date: 2016/5/23
 * Time: 16:34
 */

namespace Home\Service;//这里是一个命名空间，使用者可以自行创建
require_once './ThinkPHP/Library/Vendor/aliyun-oss-php-sdk-2.0.6.phar'; //需要引用阿里的sdk
use OSS\Core\OssException;
use OSS\OssClient;

class Upload
{
    

    /**
     * 当个上传文件并返回上传的URL地址
     * @param $filename string 要保存的文件名称 注意如果存在目录的话
     * @param $filepath string 文件在服务器（本地的绝对或者相对路径，这里的服务器实际上是本地）上的路径
     * @return bool or url
     */
    public function upload2AliyunSdkSingle($filename, $filepath)
    {
        $dir = C('ALIYUN_DIR'); //保存在阿里云的基本路径
        $endpoint = C('ALIYUN_endpoint'); //阿里云的节点信息 请自行查阅本字段意义
        $midStr = 'upload/' . date('y') . '/' . date('m') . '/' . date('d') . '/';//这里是为了保存的路径暂时以当前时间为准
        try {
            $ossClient = new OssClient(C('ALIYUN_KeyId'), C('ALIYUN_KeySecret'), $endpoint);
            //这里要放入你从阿里云获得的key和secret这里我做成了配置项读取
        } catch (OssException $e) {
            print $e->getErrorMessage();
        }
        $ossClient->setTimeout(3600);// 设置请求超时时间
        $ossClient->setConnectTimeout(10);//设置连接超时时间
        try {
            $ossClient->uploadFile(C('ALIYUN_bucket'), $dir .$midStr. $filename, $filepath);
        } catch (OssException $e) {
            return false;
        }
        $ret = str_replace('http://', 'http://' . C('ALIYUN_bucket') . '.', $endpoint . '/' . $dir . $midStr . $filename);
        //返回拼接的http url
        return $ret;
    }

    

    

    

    

    
}
