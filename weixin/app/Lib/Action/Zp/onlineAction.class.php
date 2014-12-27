<?php

/**
 * Created by PhpStorm.
 * User: alexzhu
 * Date: 14-12-20
 * Time: 下午10:05
 */
class onlineAction extends BaseAction
{

    private $keywords = '在线互动';

    public function suggest()
    {
        $this->assign('keywords', $this->keywords);
        $this->assign('active', 'suggest');
        $this->overridegetlist();
        $this->display();
    }

    public function suggestone($id)
    {
        $this->assign('keywords', $this->keywords);
        $this->assign('active', 'suggest');
        $news = M('talk');
        $condition["id"] = $id;
        $res = $news->join("__ADDONTALK__ on __TALK__.id=__ADDONTALK__.aid")->where($condition)->find();
//        echo $news->getLastSql();
//        var_dump($res);
        $this->hotadd($id);
        $this->assign('info', $res);
        $this->display();
    }

    public function sign()
    {
        if (isset($_GET['sopenid']))
            $openid = $_GET['sopenid'];
        else
            $openid = $_SESSION['openid'];
        if (isset($_GET['meetid'])){
            $meetid = $_GET['meetid'];
            $membersign = M('member_sign');
            $condition['sOpenId'] = $openid;
            $res = $membersign->where($condition)->order('id desc')->find();
            if ($res){
                $result = $res['result'];
            }
        }
        else {
            $membersign = M('member_sign');
            $condition['sOpenId'] = $openid;
            $res = $membersign->where($condition)->order('id desc')->find();
            if ($res) {
                $meetid = $res['infosId'];
                $result = $res['result'];
            }
        }
        $meet=M('addoninfos');
        $condition1['aid']=$meetid;
        $info= $meet->where($condition1)->find();
        $member=M('member');
        $condition2['sOpenId']=$openid;
        $memberinfo= $meet->where($condition2)->find();
        $this->assign('meetinfo',$info);
        $this->assign('memberinfo',$memberinfo);
        $this->assign('result',$result);
        $this->assign('keywords', $this->keywords);
        $this->assign('active', 'sign');
        $this->display();
    }

    private function hotadd($nid)
    {
        $news = M('talk');
        $condition["id"] = $nid;
        $news->where($condition)->setInc('click', 1);
    }

    public function suggestadd()
    {
        $this->assign('keywords', $this->keywords);
        $this->assign('active', 'suggest');
        if (!$_POST) {
            $this->display();
        } else {
            $info = $_POST['info'];
            $suggest = M('suggest');
            $data = array(
                'typeid' => '32',
                'sortrank' => time(),
                'channel' => '3',
                'senddate' => time(),
            );
            $res = $suggest->add($data);
            if ($res !== false) {
                $addonsuggest = M('addonsuggest');
                $data2 = array(
                    'aid' => (int)$res,
                    'typeid' => '32',
                    'body' => urlencode($res)
                );
                $res2 = $addonsuggest->add($data2);
            }
            if ($res && $res2) {
                $this->success('添加成功', U('Zp/online/suggestadd'));
            } else {
                $this->error('添加成功', U('Zp/online/suggestadd'));
            }

        }
    }

    private function overridegetlist()
    {
        $news = M('talk');
        $count_ = $list = $news->count();
        import('ORG.Util.Page');
        $page = new Page($count_, C('PAGERSIZE'));
        $page->setConfig('theme', "%upPage%   %downPage% ");
        $list = $news->join("__ADDONTALK__ ON __TALK__.id=__ADDONTALK__.aid ")->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        // var_dump($list);
        $show = $page->show();
        $this->assign('commentlist', $list);
        $this->assign('page', $show);
        $this->assign('count', $count_);
    }


}