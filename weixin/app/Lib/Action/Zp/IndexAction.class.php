<?php

class IndexAction extends BaseAction
{
    //关注回复
    public function index()
    {
        $this->assign('keywords', C('KEYWORDS')['index']);
        $this->display();
    }

    /**
     * 履职报道
     */
    public function report()
    {
        $this->overridegetlist(REPORTID);
        $this->assign('active', C('ZPSTYLE')[0]['value']);
        $this->assign('keywords', C('ZPSTYLE')[0]['key']);
        $this->display();
    }


    /*
     * 意见办理
     */
    public function deal()
    {
        $this->overridegetlist(DEALID);
        $this->assign('active', C('ZPSTYLE')[2]['value']);
        $this->assign('keywords', C('ZPSTYLE')[2]['key']);
        $this->display();
    }

    public function getcomment($id){
        $news = M('archives');
        $list = $news->join("__ADDONCOMMENT__ ON __ARCHIVES__.id=__ADDONCOMMENT__.aid where __ARCHIVES__.id=$id")->find();
        $this->assign('active', C('ZPSTYLE')[2]['value']);
        $this->assign('keywords', C('ZPSTYLE')[2]['key']);
        $this->assign('info',$list);
        $this->display();
    }

    /*
     * 代表展播
     */

    public function dbzo()
    {
        $this->overridegetlist(DBZBID);
        $this->assign('active', C('ZPSTYLE')[3]['value']);
        $this->assign('keywords', C('ZPSTYLE')[3]['key']);
        $this->display();
    }


    /*
     * 代表信息
     */
    public function zppsinfo()
    {
        $this->assign('active', C('ZPSTYLE')[1]['value']);
        $this->assign('keywords', C('ZPSTYLE')[1]['key']);
        $member_belong = M('member_belong');
        $list = $member_belong->order('type asc')->select();
        $list_ = array();
        foreach ($list as $k => $v) {
            $list_[$v['type']][] = array('id' => $v['id'],
                'name' => $v['name'],
                'type' => $v['type']
            );
        }
        $this->assign('list', $list_);
        $this->display();
//        var_dump($list_);
    }

    /**
     * 获取某个belong
     */
    public function getonebelong($bid)
    {
        $zpinfo = M('zpinfo');
        $condition['belong'] = $bid;
        $count_ = $zpinfo->where($condition)->count();
        import('ORG.Util.Page');
        $page = new Page($count_, C('PAGERSIZE'));
        $page->setConfig('theme', "%upPage%   %downPage% ");
        $list = $zpinfo->where($condition)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        // var_dump($list);
        $show = $page->show();
        $this->assign('commentlist', $list);
        $this->assign('page', $show);
        $this->assign('count', $count_);
        $this->assign('active', C('ZPSTYLE')[1]['value']);
        $this->assign('keywords', C('ZPSTYLE')[1]['key']);
        $this->display();
    }

    public function getoneperson($id)
    {
        $zpinfo = M('zpinfo');
        $list=  $zpinfo->join("__ADDONZPINFO__ ON __ZPINFO__.id=__ADDONZPINFO__.aid  ")->join("__MEMBER_BELONG__  on __ZPINFO__.belong=__MEMBER_BELONG__.id where __ZPINFO__.id=$id")->find();
     //   $sql = "select zp.*,bm.name as bname from __ZPINFO__ as   zp left join __MEMBER_BELONG__ as bm on zp.belong=bm.id where zp.id=$id";
       // $list = $zpinfo->query($sql);
        $this->assign('active', C('ZPSTYLE')[1]['value']);
        $this->assign('keywords',$list['name'].'-'.$list['title']);
        $this->assign('info', $list);
        $this->display();
    }


    public function suggestadd()
    {
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
                $this->success('添加成功', U('Zp/Index/suggestadd'));
            } else {
                $this->error('添加成功', U('Zp/Index/suggestadd'));
            }

        }
    }

    private function overridegetlist($type)
    {
        $news = M('archives');
        $condition['typeid'] = $type;
        $count_ = $list = $news->where($condition)->count();
        import('ORG.Util.Page');
        $page = new Page($count_, C('PAGERSIZE'));
        $page->setConfig('theme', "%upPage%   %downPage% ");
       if($type==DEALID)
         $list = $news->join("__ADDONCOMMENT__ ON __ARCHIVES__.id=__ADDONCOMMENT__.aid where __ARCHIVES__.typeid=$type")->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
       else
        $list = $news->where($condition)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        // var_dump($list);
        $show = $page->show();
        $this->assign('commentlist', $list);
        $this->assign('page', $show);
        $this->assign('count', $count_);
    }

}