<?php
/**
 * Created by PhpStorm.
 * User: zjy202
 * Date: 2017/10/9
 * Time: 17:41
 */
require "Phpqrcode/phpqrcode.php";
class Makeqrcode {
    public static function png($text,$filename=false, $errorCorrectionLevel=0, $matrixPointSize=3,$margin=4){
       return  QRcode::png($text,$filename, $errorCorrectionLevel, $matrixPointSize, $margin);
    }
}