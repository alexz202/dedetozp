<?php
define('WORKER',13);
define('STONE',14);
define('RENDA',16);


class newsAction extends BaseAction{

	//关注回复
	public function index(){
        $keywords=C('KEYWORDS');
        $this->assign('keywords',$keywords['index']);
		$this->display();
	}
    public function getnewslist($type=WORKER)
    {
        $news=M('archives');
        $condition['typeid']=$type;
        $newslist=C('NEWSLIST');
        foreach($newslist as $k=>$value){
            if($type==$value['id']){
                $keywords=$value['value'];
                $this->assign('keywords',$keywords);
            }
        }
        $this->assign('hit',$type);
        $this->assign('navlist',$newslist);
        $count_=$list=$news->where($condition)->count();
        import('ORG.Util.Page');
        $page=new Page($count_,C('PAGERSIZE'));
        $page->setConfig('theme',"%upPage%   %downPage% ");
        $list=$news->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
       // var_dump($list);
        $show=$page->show();
        $this->assign('commentlist',$list);
        $this->assign('page',$show);
        $this->assign('count',$count_);
        $this->display();
    }
    public function getone($nid){
        $news=M('archives');
        $condition["id"]=$nid;
        $res=$news->join("__ADDONARTICLE__ on __ARCHIVES__.id=__ADDONARTICLE__.aid")->where($condition)->find();
//        echo $news->getLastSql();
//        var_dump($res);
        $this->hotadd($nid);
        $this->assign('info',$res);
        $this->display();
    }
    private function hotadd($nid){
        $news=M('archives');
        $condition["id"]=$nid;
        $news->where($condition)->setInc('click',1);
    }


}