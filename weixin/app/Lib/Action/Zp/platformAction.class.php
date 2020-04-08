<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 17-5-7
 * Time: 下午11:10
 */
class platformAction extends BaseAction{

    public function indexnew(){
        $newsModel=M('archives');
        $condtion_news=array(
            'typeid'=>array('in',array(35,36,38,39,41,43))
        );
        $list=$newsModel->where($condtion_news)->order('id desc')->limit(4)->select();
        $this->assign('list',$list);
        $this->display();
    }

	public function index(){
		$newsModel=M('archives');
		$condtion_news=array(
			'typeid'=>array('in',array(35,36,38,39,41))
		);
		$list=$newsModel->where($condtion_news)->order('id desc')->limit(4)->select();
		$this->assign('list',$list);
		$this->display();
	}

	public function detail($type){
		$model=M('arctype');
		$condition['id']=intval($type);
		$info=$model->where($condition)->find();
		$this->assign('info',$info);
		$newsModel=M('archives');
		$condtion_news=array(
			'typeid'=>$type
		);
		$list=$newsModel->where($condtion_news)->order('id desc')->limit(4)->select();
		$this->assign('list',$list);
		$this->assign('type',$type);

		$newslist=C('NEWSLIST');
		foreach($newslist as $k=>$value){
			if($type==$value['id']){
				$keywords=$value['value'];
				$this->assign('keywords',$keywords);
			}
		}

		$this->display();
	}

	public function duty($type){
		$model=M('arctype');
		$condition['id']=intval($type);
		$info=$model->where($condition)->find();
		$this->assign('info',$info);
//		$newsModel=M('archives');
		$this->assign('type',$type);

		$newslist=C('NEWSLIST');
		foreach($newslist as $k=>$value){
			if($type==$value['id']){
				$keywords=$value['value'];
				$this->assign('keywords',$keywords);
			}
		}

		$this->display();
	}


}