<?php
namespace Admin\Controller;
use Think\Controller;

/**
*文章内容管理
*/
class ContentController extends CommonController
{
    public function index()
    {
        $conds=array();
        $title=$_get['title'];
        if ($title) {
            $conds['title']=$title;
        }
        if ($_GET['catid']) {
            $conds['catid']=intval($_REQUEST['catid']);
        }
        $page=$_REQUEST['P']?$_REQUEST['P']:1;
        $pageSize=10;
        $news=D('News')->getNews($conds, $page, $pageSize);
        $count=D('News')->getNewsCount($conds);

        $res=new \Think\Page($count, $pageSize);
        $pageres=$res->show();
        $positions=D('Position')->getNormalPositions();
        $this->assign('pageres', $pageres);
        $this->assign('news', $news);
        $this->assign('positions', $positions);
        $barMenus = D("Menu")->getBarMenus();
        $this->assign('webSiteMenu', $barMenus);
        $this->display();
    }

    public function add()
    {
        if ($_POST) {
            if (!isset($_POST['title']) || !$_POST['title']) {
                return jsonResult(0, '标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']){
                return jsonResult(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']){
                return jsonResult(0,'文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']){
                return jsonResult(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']){
                return $this->save($_POST);
            }
            $newsId=D('News')->insert($_POST);
            if($newsId){
                $newsContentData['content']=$_POST['content'];
                $newsContentData['news_id']=$newsId;
                $cId=D('NewsContent')->insert($newsContentData);
                if($cId){
                    return jsonResult(1,'新增成功');
                }else{
                    return jsonResult(1,'主表插入成功，附表插入失败');
                }
            }else{
                return jsonResult(0, '新增失败');    
            }
        } else {
            $webSiteMenu=D('Menu')->getBarMenus();
            $titleFontColor=C('TITLE_FONT_COLOR');
            $copyFrom=C('COPY_FROM');
            $this->assign('webSiteMenu',$webSiteMenu);
            $this->assign('titleFontColor',$titleFontColor);
            $this->assign('copyfrom',$copyFrom);
            $this->display();
        }
    }

    public function edit(){
        $newsId=$_GET['id'];
        if(!$newsId){
            $this->redirect('/admin.php?c=content');
        }
        $news=D('News')->find($newsId);
        if(!$news){
            $this->redirect('/admin.php?c=content');
        }
        $newsContent=D('NewsContent')->find($newsId);
        if($newsContent){
            $news['content']=$newsContent['content'];
        }

        $webSiteMenu=D('Menu')->getBarMenus();
        $this->assign('webSiteMenu',$webSiteMenu);
        $this->assign('titleFontColor',C('TITLE_FONT_COLOR'));
        $this->assign('copyfrom',C('COPY_FORM'));
        $this->assign('news',$news);
        $this->display();
    }

    public function save($data){
        $newsId=$data['news_id'];
        unset($data['news_id']);

        try{
            $id=D('News')->updateById($newsId,$data);
            $newsContentData['content']=$data['content'];
            $condId=D('NewsContent')->updateNewsById($newsId,$newsContentData);
            if($id===false || $condId===false){
                return jsonResult(0,'更新失败');
            }
            return jsonResult(1,'更新成功');
        }catch(Exception $e){
            return jsonResult(0,$e->getMessage());
        }
    }

    public function setStatus(){
        try{
            if($_POST){
                $id=$_POST['id'];
                $status=$_POST['status'];
                if(!$id){
                    return jsonResult(0,'ID不存在');
                }
                $res=D('News')->updateStatusById($id,$status);
                if($res){
                    return jsonResult(1,'操作成功');
                }else{
                    return jsonResult(0,'操作失败');
                }
            }
            return jsonResult(0,'没有提交的内容');
        }catch(Exception $e){
            return jsonResult(0,$e->getMessage());
        }
    }

    public function listorder(){
        $listorder=$_POST['listorder'];
        $jumpUrl=$_SERVER['HTTP_REFERER'];
        $errors=array();
        try{
            if($listorder){
                foreach($listorder as $newsId => $V){
                    $id=D('News')->updateNewsListorderById($newsId,$v);
                    if($id===false){
                        $errors[]=$newsId;
                    }
                }
                if($errors){
                    return jsonResult(0,'排序失败-'.implode(',',$errors),array(
                        'jump_url'=>$jumpUrl
                    ));
                }
                return jsonResult(1,'排序成功',array('jump_url'=>$jumpUrl));
            }
        }catch(Exception $e){
            return jsonResult(0,$e->getMessage());
        }
        return jsonResult(0,'数据排序失败',array('jump_url'=>$jumpUrl));
    }

    public function push(){
        $jumpUrl=$_SERVER['HTTP_REFERER'];
        $positonId=intval($_POST['positon_id']);
        $newsId =$_POST['push'];

        if(!$newsId || !is_array($newsId)){
            return jsonResult(0,'请选择推荐的文章ID进行推荐');
        }
        if(!$positonId){
            return jsonResult(0,'没有选择推荐位');
        }
        try{
            $news=D('News')->getNewsByNewsIdIn($newsId);
            if(!$news){
                return jsonResult(0,'没有相关内容');
            }

            foreach($news as $new){
                $data=array(
                    'position_id'=>$positonId,
                    'title'=>$new['title'],
                    'thumb'=>$new['thumb'],
                    'news_id'=>$new['news_id'],
                    'status'=>1,
                    'create_time'=>$new['create_time']
                );
                $position=D('PositionContent')->insert($data);
            }
        }catch(Exception $e){
            return jsonResult(0,$e->getMessage());
        }
        return jsonResult(1,'推荐成功',array('jump_url'=>$jumpUrl));
    }
}
