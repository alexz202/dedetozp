<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 17-5-6
 * Time: 下午10:12
 */
class MsgModel extends Model{

  protected  $tableName="msg";

  public function addMsg($params){
     return  $this->add($params);
  }

  public function reMsg($id,$remsg){
      $condition=array('id'=>intval($id));
      $data=array('remsg'=>$remsg,'updateTime'=>time());
      return $this->where($condition)->save($data);
  }

  public function getToMyMsg($mid){
		  $condition=array(
			  'toMid'=>$mid,
		  );
		  return $info= $this->where($condition)->order('updateTime desc')->select();
  }

  public function getMyAddMsg($mid){
      $condition=array(
          'fMid'=>$mid,
      );
      return $info= $this->where($condition)->order('updateTime desc')->select();

  }
}
