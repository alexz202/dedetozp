<?php

class IndexAction extends BaseAction
{
    public function index()
    {
        $openid = $_SESSION['openid'];
        $nickname = $_SESSION['nickname'];
        $this->display();
    }

	public function treeBanner(){
		$openid = $_SESSION['openid'];
		$nickname = $_SESSION['nickname'];
		$keyword="浦东绿化林业";
		$this->assign('keywords',$keyword);
		$this->display();
	}

}
