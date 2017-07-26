<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class ImageController extends CommonController{

    public function ajaxuploadimage(){
        $upload =D('UploadImage');
        $res=$upload->imageUpload();
        if($res===false){
            return jsonResult(0,'上传失败','');
        }else{
            return jsonResult(1,'上传成功',$res);
        }
    }

    public function kindupload(){
        $upload=D('UploadImage');
        $res=$upload->upload();
        if($res===false){
            return jsonResult(1,'上传失败');
        }
        return jsonResult(0,$res);
    }    
}
?>