<?php
class IndexAction extends BaseAction{
	//关注回复
	public function index(){
        $this->assign('keywords',C('KEYWORDS')['index']);
		$this->display();
	}

}