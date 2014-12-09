<?php
/**
 * simple curd sql to complete dede to db
 * User: alexzhu
 * Date: 14-12-9
 * Time: 下午5:13
 */
function add($params,$condition=array()){
    $table=$condition['table'];
    unset($condition['table']);
    $cols = implode(",", array_keys($params));
    $strval=_getStrVal($params);
    $sql="insert into ".$table." (".$cols.")values(".$strval.")";
    return $sql;
}
function save($param,$condition=array()){
    $table=$condition['table'];
    unset($condition['table']);
    $strval=getStrKeyVal($param);
    $strcondition=getStrKeyVal($condition);
    $sql="UPDATE ".$table." SET ".$strval." where ".$strcondition;
    return $sql;
}
function delete($condition=array()){
    $table=$condition['table'];
    unset($condition['table']);
    $strcondition=getStrKeyVal_where($condition);
    $sql="DELETE FROM ".$table." WHERE ".$strcondition;
    return $sql;
}
 function _getStrVal($params){
    $vals = array_values( $params );
    $strVal = "";
    foreach( $vals as $value ){
        if( $strVal == "" ){
            $strVal = "'".$value."'";
        }
        else{
            $strVal = $strVal.", '".$value."'";
        }
    }
    return $strVal;
}

function getStrKeyVal($params){
    $strVal="";
    foreach($params as $k=>$value ){
        if( $strVal == "" ){
            $strVal = $k."='".$value."'";
        }
        else{
            $strVal = $strVal.",".$k."='".$value."'";
        }
    }
    return $strVal;
}

function getStrKeyVal_where($params){
    $strVal="";
    foreach($params as $k=>$value ){
        if( $strVal == "" ){
            $strVal = $k."='".$value."'";
        }
        else{
            $strVal = $strVal." and ".$k."='".$value."'";
        }
    }
    return $strVal;
}
