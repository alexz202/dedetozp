<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 17-5-6
 * Time: 下午10:12
 */
class ReMsgModel extends Model{

	protected  $tableName="remsg";


	public function reMsg($params){
		$this->add($params);
	}

	public function getsByMsgId($msgId,$orderBy='id asc'){
		$condition=array(
		'msgId'=>$msgId,
		);
		return $this->where($condition)->order($orderBy)->select();
	}

	public function update($id,$params){
		$condition=array(
			'id'=>$id,
		);
		return $this->where($condition)->save($params);
	}

	public function del($id){
		$condition=array(
			'id'=>$id,
		);
		return $this->where($condition)->delete();
	}
}
