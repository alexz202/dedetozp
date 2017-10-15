<?php



class newsAction extends BaseAction{

	public function index(){
        $keywords=C('KEYWORDS');
        $this->assign('keywords',$keywords['index']);
		$this->display();
	}
    public function getnewslist($type=HOTREPO_I)
    {
        $news=M('archives');
        $condition['typeid']=$type;
        $condition['arcrank']=0;
        $newslist=C('NEWSLIST');
		$navlist=array();
        foreach($newslist as $k=>$value){
            if($type==$value['id']){
                $keywords=$value['value'];
                $this->assign('keywords',$keywords);
            }
			if($value['nav']==1){
				$navlist[$k]=$value;
			}

        }
        $this->assign('hit',$type);
        $this->assign('navlist',$navlist);
        $count_=$list=$news->where($condition)->count();
        import('ORG.Util.Page');
        $page=new Page($count_,C('PAGERSIZE'));
        $page->setConfig('theme',"%upPage%   %downPage% ");
        $list=$news->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
      //  var_dump($list);
    //    die();
        $show=$page->show();
        $this->assign('commentlist',$list);
        $this->assign('page',$show);
        $this->assign('count',$count_);
        $this->assign('type',$type);
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


	public function gethudonglist($type=ENROLL_I)
	{
		$news=M('archives');
		$condition['typeid']=$type;
		$condition['arcrank']=0;
		$newslist=C('HUDONG');
		$navlist=array();
		foreach($newslist as $k=>$value){
			if($type==$value['id']){
				$keywords=$value['value'];
				$this->assign('keywords',$keywords);
			}
			if($value['nav']==1){
				$navlist[$k]=$value;
			}

		}
		$this->assign('hit',$type);
		$this->assign('navlist',$navlist);
		$count_=$list=$news->where($condition)->count();
		import('ORG.Util.Page');
		$page=new Page($count_,C('PAGERSIZE'));
		$page->setConfig('theme',"%upPage%   %downPage% ");
		$list=$news->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		//  var_dump($list);
		//    die();
		$show=$page->show();
		$this->assign('commentlist',$list);
		$this->assign('page',$show);
		$this->assign('count',$count_);
		$this->assign('type',$type);
		$this->display();
	}

	public function getonegas($nid){
		$news=M('gas');
		$condition["id"]=$nid;
		$res=$news->join("__ADDONGAS__ on __GAS__.id=__ADDONGAS__.aid")->where($condition)->find();
		$path='/dedetozp/mng/uploads/files/';
		$res['file']=$path.$res['filename'];
//        echo $news->getLastSql();
//        var_dump($res);
		$this->hotadd($nid,1);
		$this->assign('info',$res);
		$this->display();
	}

    private function hotadd($nid,$type=0){
		if($type==1){
			$news=M('gas');
		}else{
			$news=M('archives');
		}
        $condition["id"]=$nid;
        $news->where($condition)->setInc('click',1);
    }


}