<?php
namespace Admin\Controller;
use Think\Controller;

class PositionController extends CommonController{
    public function index(){
        $data['status']=array('neq',-1);
        $positions=D('Position')->select($data);
        $this->assign('positions',$positions);
        $this->assign('nav','推荐位管理');
        $this->display();
    }

    public function add(){
        if(IS_POST){
            if(!isset($_POST['name']) || !$_POST['name']){
                return jsonResult(0,'推荐位名称为空');
            }

            if($_POST['id']){
                return $this->save($_POST);
            }
            try{
                $id=D('Position')->insert($_POST);
                if($id){
                    return jsonResult(1,'新增成功',$id);
                }
                return jsonResult(0,'新增失败',$id);
            }catch(Exception $e){
                return jsonResult(0,$e->getMessage());
            }
            return jsonResult(0,'新增失败',$newsId);
        }else{
            $this->display();
        }
    }

    public function edit(){
        $data=array(
            'status'=>array('neq',-1)
        );
        $id=$_REQUEST['id'];
        $positions=D('Position')->find($id);
        $this->assign('vo',$positions);
        $this->display();
    }

    public function save($data){
        $id=$data['id'];
        unset($data['id']);
        try{
            $id=D('Position')->updateById($id,$data);
            if($id===false){
                return jsonResult(0,'更新失败');
            }
            return jsonResult(1,'更新成功');
        }catch(exception $e){
            return jsonResult(0,$e->getMessage());
        }
    }

    public function setStatus(){
        try{
            if($_POST){
                $id=$_POST['id'];
                $status=$_Post['status'];
                $res=D('Position')->updateStatusById($id,$status);
                if($res){
                    return jsonResult(1,'操作成功');
                }
                return jsonResult(0,'操作失败');
            }
        }catch(Exception $e){
            return jsonResult(0,$e->getmessage());
        }
        return jsonResult(0,'没有提交的内容');
    }
}
?>