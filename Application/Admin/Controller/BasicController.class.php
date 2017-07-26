<?php
namespace Admin\Controller;
use Think\Controller;

class BasicController extends CommonController
{
    public function index()
    {
        $result=D('Basic')->select();
        $this->assign('vo', $result);
        $this->assign('type', 1);
        $this->display();
    }

    public function add()
    {
        if ($_POST) {
            if (!$_POST['title']) {
                return jsonResult(0, '站点信息不能为空');
            }
            if (!$_POST['keywords']) {
                return jsonResult(0, '站点关键词不能为空');
            }
            if (!$_POST['description']) {
                return jsonResult(0, '站点描述不能为空');
            }
            D('Basic')->save($_POST);
        } else {
            return jsonResult(0, '没有提交的数据');
        }
        return jsonResult(1, '配置成功！');
    }

    public function cache()
    {
        $this->assign('type', 2);
        $this->display();
    }
}
