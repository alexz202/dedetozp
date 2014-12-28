<?php

class IndexAction extends BaseAction
{
    //关注回复
    public function index()
    {
        $openid = $_SESSION['openid'];
        $nickname = $_SESSION['nickname'];
        // file_put_contents('log/testindex',date('Y-m-d h:i:s').$openid.'|'.$nickname.'|'.$openid1.'|'.$nickname1."\r\n",FILE_APPEND);
        $keywords = C('KEYWORDS');
        $this->assign('keywords', $keywords['index']);
        $this->display();
    }

    public function search()
    {
        $keywords = $_REQUEST['keywords'];
        if (isset($keywords)) {
            $countall = 0;
            $tbarr = array('archives', 'zpinfo', 'talk');
            $typeindex = array(
                '13' => 'index.php?g=Zp&m=news&a=getone&nid=',
                '14' => 'index.php?g=Zp&m=news&a=getone&nid=',
                '16' => 'index.php?g=Zp&m=news&a=getone&nid=',
                '18' => 'index.php?g=Zp&m=news&a=getone&nid=',
                '19' => 'index.php?g=Zp&m=Index&a=getoneperson&id=',
                '20' => 'index.php?g=Zp&m=Index&a=getcomment&id=',
                '24' => 'index.php?g=Zp&m=online&a=suggestone&id='
            );
            foreach ($tbarr as $v) {
                $$v = M($v);
                $condition["title"] = array("like", "%" . $keywords . "%");
                $count = $$v->where($condition)->count();
                $countall = $countall + $count;
                $sql[] = "select id,typeid,title,click,senddate from tp_$v where title like '%$keywords%'";
            }
            $sqlstr = join(' union all ', $sql);
            import('ORG.Util.Page');
            $page = new Page($countall, C('PAGERSIZE'));
            $page->setConfig('theme', "%upPage%   %downPage% ");
            $page->parameter = "&keywords=" . urlencode($keywords);
            $sqlall = $sqlstr . " limit $page->firstRow,$page->listRows";
            $news = M('archives');
            $list = $news->query($sqlall);
            foreach ($list as $value) {
                $typeid = $value['typeid'];
                $url = $typeindex[$typeid];
                $list_[] = array('id' => $value['id'],
                    'title' => $value['title'],
                    'click' => $value['click'],
                    'senddate' => $value['senddate'],
                    'url' => $url,
                );
            }
            $show = $page->show();
            $this->assign('commentlist', $list_);
            $this->assign('page', $show);
            $this->assign('count', $countall);
        }
        $this->display();
    }

    /**
     * 履职报道
     */
    public function report()
    {
        $this->overridegetlist(REPORTID);
        $zpstyle = C('ZPSTYLE');
        $this->assign('active', $zpstyle[0]['value']);
        $this->assign('keywords', $zpstyle[0]['key']);
        $this->display();
    }


    /*
     * 意见办理
     */
    public function deal()
    {
        $this->overridegetlist(DEALID);
        $zpstyle = C('ZPSTYLE');
        $this->assign('active', $zpstyle[2]['value']);
        $this->assign('keywords', $zpstyle[2]['key']);
        $this->display();
    }

    public function getcomment($id)
    {
        $news = M('archives');
        $this->hotadd($news, $id);
        $list = $news->join("__ADDONCOMMENT__ ON __ARCHIVES__.id=__ADDONCOMMENT__.aid where __ARCHIVES__.id=$id")->find();
        $zpstyle = C('ZPSTYLE');
        $this->assign('active', $zpstyle[2]['value']);
        $this->assign('keywords', $zpstyle[2]['key']);
        $this->assign('info', $list);
        $this->display();
    }

    /*
     * 代表展播
     */

    public function dbzo()
    {
        $this->overridegetlist(DBZBID);
        $zpstyle = C('ZPSTYLE');
        $this->assign('active', $zpstyle[3]['value']);
        $this->assign('keywords', $zpstyle[3]['key']);
        $this->display();
    }


    /*
     * 代表信息
     */
    public function zppsinfo()
    {
        $zpstyle = C('ZPSTYLE');
        $this->assign('active', $zpstyle[1]['value']);
        $this->assign('keywords', $zpstyle[1]['key']);
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
        $zpstyle = C('ZPSTYLE');
        $this->assign('active', $zpstyle[1]['value']);
        $this->assign('keywords', $zpstyle[1]['key']);
        $this->display();
    }

    public function getoneperson($id)
    {
        $zpinfo = M('zpinfo');
        $this->hotadd($zpinfo, $id);
        $list = $zpinfo->join("__ADDONZPINFO__ ON __ZPINFO__.id=__ADDONZPINFO__.aid  ")->join("__MEMBER_BELONG__  on __ZPINFO__.belong=__MEMBER_BELONG__.id where __ZPINFO__.id=$id")->find();
        //   $sql = "select zp.*,bm.name as bname from __ZPINFO__ as   zp left join __MEMBER_BELONG__ as bm on zp.belong=bm.id where zp.id=$id";
        // $list = $zpinfo->query($sql);
        $zpstyle = C('ZPSTYLE');
        $this->assign('active', $zpstyle[1]['value']);
        $this->assign('keywords', $list['name'] . '-' . $list['title']);
        $this->assign('info', $list);
        $this->display();
    }

    private function hotadd($obj, $nid)
    {
        $condition["id"] = $nid;
        $obj->where($condition)->setInc('click', 1);
    }

    private function overridegetlist($type)
    {
        $news = M('archives');
        $condition['typeid'] = $type;
        $count_ = $list = $news->where($condition)->count();
        import('ORG.Util.Page');
        $page = new Page($count_, C('PAGERSIZE'));
        $page->setConfig('theme', "%upPage%   %downPage% ");
        if ($type == DEALID)
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