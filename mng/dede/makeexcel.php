<?php
/**
 * Created by PhpStorm.
 * User: alexzhu
 * Date: 14-12-24
 * Time: 下午6:07
 */

class makeExcel{
    public function __construct(){
    }

   public static  function makeEXCEL($tagarr,$valuearr,$output){
        self::setExcelHead($output);
        self::parseRESTOEXCEL($tagarr,$valuearr);
        exit();
   }
    protected function parseRESTOEXCEL($tagarr,$array){
        foreach($tagarr as $value){
            echo $value."\t";
        }
        echo "\t\n";
        foreach ($array as $value){
            foreach($value as $v){
                echo $v."\t";
            }
            echo "\t\n";
        }
        //return $arr;
    }
    protected function setExcelHead($output){
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=$output.xls");
        header("Pragma:no-cache");
        header('Expires:0');
    }


}