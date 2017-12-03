<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 14-10-5
 * Time: 下午1:49
 */
class WxuserAction extends UserAction{
   public function index(){
       $Wxuser=M('wxmember');
       $count=$Wxuser->count();
       $page=new Page($count,C('PAGESIZE'));
       $list= $Wxuser->limit($page->firstRow,$page->listRows)->select();
       $show=$page->show();
       $page->setConfig('theme'," %first% %prePage% %upPage%  %linkPage% %downPage%%nextPage% %end% %nowPage%/%totalPage% 页");
       $this->assign('list',$list);
       $this->assign('page',$show);
       $this->display();
   }
    public function del(){
        $id=$_GET['id'];
        $Wxuser=M('wxmember');
        $condition['id']=$id;
        $res=$Wxuser->where($condition)->delete();
        if($res)
            $this->success(L('update_success'),U('Wxuser/index', array(
                'token' => $this->token,
            )));
        else
            $this->success(L('update_fail'),U('Wxuser/index', array(
                'token' => $this->token,
            )));
    }



}