<?php



class newsAction extends BaseAction{

	private $defaultKeyword='浦东林业绿化';
	public function index(){
        $keywords=C('KEYWORDS');
        $this->assign('keywords',$keywords['index']);
		$this->display();
	}


	private function getKeywordsByTypeId($typeId){
		$newslist=C('NEWSLIST');
		$huodong=C('HUDONG');
		$service=C('SERVICE');

		$keylist=array();
		$list=array_merge($newslist,$huodong,$service);
		foreach($list as $v){
			$keylist[$v['id']]=$v;
		}
		if(isset($keylist[$typeId]))
			return $keylist[$typeId]['value'];
		else
			return $this->defaultKeyword;
	}
    public function getnewslist($type=HOTREPO_I)
    {
        $news=M('archives');
        $condition['typeid']=$type;
        $condition['arcrank']=0;
        $newslist=C('NEWSLIST');
		$keyword=$this->getKeywordsByTypeId($type);
		$this->assign('keywords',$keyword);
		$navlist=array();
        foreach($newslist as $k=>$value){
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
		$keyword=$this->getKeywordsByTypeId($res['typeid']);
		$this->assign('keywords',$keyword);

        $this->assign('info',$res);
        $this->display();
    }




	public function gethudonglist($type=ENROLL_I)
	{
		$news=M('archives');
		$condition['typeid']=$type;
		$condition['arcrank']=0;
		$newslist=C('HUDONG');
		$keyword=$this->getKeywordsByTypeId($type);
		$this->assign('keywords',$keyword);
		$navlist=array();
		foreach($newslist as $k=>$value){
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