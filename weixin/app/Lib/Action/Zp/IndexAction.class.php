<?php
class IndexAction extends BaseAction{
	//关注回复
	public function index(){
        $this->assign('keywords',C('KEYWORDS')['index']);
		$this->display();
	}
    public function zppsindex(){
             $member_belong=M('member_belong');
        $list=$member_belong->order('type asc')->select();
        $list_=array();
        foreach($list as $k=>$v){
            $list_[$v['type']][]=array('id'=>$v['id'],
                                    'name'=>$v['name'],
                                    'type'=>$v['type']
                               );
        }
        $this->assign('list',$list_);
        $this->display();
//        var_dump($list_);
    }

    /**
     * 获取某个belong
     */
    public function getonebelong($bid){


        
    }

    public function getoneperson(){

    }
    public function suggestadd(){
        if(!$_POST){
            $this->display();
        }
        else{
            $info=$_POST['info'];
            $suggest=M('suggest');
            $data=array(
                'typeid'=>'32',
                'sortrank'=>time(),
                'channel'=>'3',
                'senddate'=>time(),
            );
            $res=$suggest->add($data);
            if($res!==false){
                $addonsuggest=M('addonsuggest');
                $data2=array(
                    'aid'=>(int) $res,
                    'typeid'=>'32',
                    'body'=>urlencode($res)
                );
              $res2=  $addonsuggest->add($data2);
            }
            if($res&&$res2){
                $this->success('添加成功',U('Zp/Index/suggestadd'));
            }else{
                $this->error('添加成功',U('Zp/Index/suggestadd'));
            }

        }
    }

}