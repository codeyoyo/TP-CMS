<?php
namespace Common\Model;

class BasicModel {
    public function save($data=array()){
        if(!$data){
            throw_exception('没有提交的数据');
        }
        $id=F('basic_web_config',$data);
    }

    public function select(){
        return F('basic_web_config');
    }
}
?>