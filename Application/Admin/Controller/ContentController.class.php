<?php
namespace Admin\Controller;

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
        $barMenus = D("Menu")->getBarMens();
        $this->assign('webSiteMenu', $barMenus);
        $this->display();
    }

    public function add()
    {
        if ($_POST) {
            if (!isset($_POST['title']) || !$_POST['title']) {
                return jsonResult(0, '标题不存在');
            }
        } else {
            return jsonResult(0, '新增失败');
        }
    }
}
