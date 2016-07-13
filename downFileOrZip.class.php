<?php
/**
 * Created by PhpStorm.
 * User: linwang5
 * Date: 2016/5/23
 * Time: 16:34
 */

class Updown
{
       /**
     * 下载文件的接口，最低情况要传入文件的相对地址,目前支持单个文件的下载
     * 或者直接用 import('ORG.Net.Http');
     * Http::download($file) 但需开通 php_fileinfo.dll
     * @param $file
     * @param $name
     */
    public function download($file, $name)
    {
        $fileName = $name ? $name : pathinfo($file, PATHINFO_FILENAME);
        $filePath = realpath($file);
        $fp = fopen($filePath, 'rb');
        if (!$filePath || !$fp) {
            header('HTTP/1.1 404 Not Found');
            echo "Error: 404 Not Found.(server file path error)<!-- Padding --><!-- Padding -->
            <!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding -->
            <!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding -->
            <!-- Padding --><!-- Padding -->";
            exit;
        }

        $fileName = $fileName . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        $encoded_filename = urlencode($fileName);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);

        header('HTTP/1.1 200 OK');
        header('Pragma: public');
        header('Expires: 0');
        header('Content-type: application/octet/-stream');
        header('Content-Length: ' . filesize($filePath));
        header('Accept-Ranges: bytes');
        header('Accept-Length: ' . filesize($filePath));

        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/', $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } elseif (preg_match('/Firefox/', $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        }

        //        ob_end_clean(); //有些情况可能需要调用此函数

        //输出文件内容
        fpassthru($fp);
    }

    /**
     * 将某个文件夹的内容打包为zip下载
     * @param $dir 这里不能包含内部文件件
     * @param $zipfile 保存的路径
     */
    public function down2Zip($dir, $zipfile)
    {
        import('ORG.Net.FileToZip');
        $handler = opendir($dir);
        $download_file = array();
        $i = 0;
        while (($filename = readdir($handler)) !== false) {
            if ($filename != '.' && $filename != '..') {
                $download_file[$i++] = $filename;
            }
        }

        closedir($handler);
        $scandir = new \traverseDir($dir, $zipfile);
        $scandir->tozip($download_file);
    }

    /**
     * 将某个文件夹的内容打包为zip下载
     * @param $dir 文件的目录
     * @param string $saveRootDir 压缩后是否保留指定目录的根目录 默认 false
     * @param null $filename 要下载的文件名称 不需要加.zip后缀
     * @example down2ZipDir('./Public/HomeWork');
     */

    public function down2ZipDir($dir, $saveRootDir = false, $filename = null)
    {
        if (!is_dir($dir)) {
            die('Error: ' . $dir . ' is not a directory');
        }
        $removeDir = $dir;
        if ($saveRootDir === true) {
            $removeDir = substr($dir, 0, strrpos($dir, '/'));
        }
        import('ORG.Net.PclZip');
        $zipname = date('YmdHis', time());

        $zipname = $zipname . '.zip';
        $archive = new \PclZip($zipname);
        $v_list = $archive->create($dir,
            PCLZIP_OPT_REMOVE_PATH, $removeDir);
        if ($v_list == 0) {
            unlink($zipname);
            die("Error:" . $archive->errorInfo(true));
        }
        $this->download($zipname, $filename);
        unlink($zipname);
    }

   
