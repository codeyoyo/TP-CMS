<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Excetion;

class PositioncontentController extends CommonController{

    public function index(){
        $positions=D('Position')->getNormalPositions();
        $data['status']=array('neq',-1);
        if($_GET['title']){
            $data['title']=trim($_GET['title']);
            $this->assign('title',$data['title']);
        }

        $data['position_id']=$_GET['position_id']?intval($_GET['position_id']):$positions[0]['id'];
        $contents=D('PositionContent')->select($data);
        $this->assign('positions',$positions);
        $this->assign('contents',$contents);
        $this->assign('positionId',$data['position_id']);
        $this->display();
    }

    public function add(){
        if($_POST){
            if(!isset($_POST['position_id']) || !$_POST['position_id']){
                return jsonResult(0,'推荐位ID不能为空');
            }
            if(!isset($_POST['title']) || !$_POST['title']){
                return jsonResult(0,'推荐位标题不能为空');
            }
            if(!$_POST['url'] && !$_POST['news_id']){
                return jsonResult(0,'url和news_id不能同时为空');
            }
            if(!isset($_POST['thumb']) || !$_POST['thumb']){
                if($_POST['news_id']){
                    $res=D('News')->find($_POST['news_id']);
                    if($res && is_array($res)){
                        $_POST['thumb']=$res['thumb'];
                    }
                }else{
                    return jsonResult(0,'图片不能为空');
                }
            }
            if($_POST['id']){
                return $this->save($_POST);
            }
            try{
                $id=D('PositionContent')->insert($_POST);
                if($id){
                    return jsonResult(1,'新增成功');
                }
                return jsonResult(0,'新增失败');
            }catch(Exception $e){
                return jsonResult(0,$e->getMessage());
            }
        }else{
            $positions=D('Position')->getNormalPositionss();
            $this->assign('positions',$positions);
            $this->display();
        }
    }

    public function edit(){
        $id=$_REQUEST['id'];
        $position=D('PositionContent')->find($id);
        $positions=D('Position')->getNormalPositions();
        $this->assign('positions',$position);
        $this->assign('vo',$position);
        $this->display();
    }

    public function save($data){
        $id=$data['id'];
        unset($data['id']);
        try{
            $resId=D('PositionContent')->updateById($id,$data);
            if($resId===false){
                return jsonResult(0,'更新失败');
            }
            return jsonResult(1,'更新成功');
        }catch(Exception $e){
            return jsonResult(0,$e->getMessage());
        }
    }

    public function setStatus(){
        $data=array(
            'id'=>intval($_POST['id']),
            'status'=>intval($_POST['status'])
        );
    }

    public function listorder(){
        return parent::listorder('PositionContent');
    }
}

?>