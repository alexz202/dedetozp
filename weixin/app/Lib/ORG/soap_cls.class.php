<?php
class jxsoapClient
{
    protected $soapClient_=null;
    public function __construct($wsdl){
       $this->soapClient_=new soapClient($wsdl);
    }

    public function getExchangeUse($phoneNo,$type='APP',$sn=''){
        $params=array(
            'phoneNo'=>$phoneNo,
            'Type'=>$type,
            'SN'=>'',
        );
     return $this->soapClient_->WontonExchangeUse($params);
    }
    public function searchFromPhoneNo($phoneNo,$sn=''){
        $params=array(
            'phoneNo'=>$phoneNo,
            'SN'=>$sn,
        );
        return $this->soapClient_->WontonExchangeFromPhoneNo($params);
    }

    public function ExchangeInfoQuery($ExchangeCode,$PosId,$SN){
        $params=array(
          'ExchangeCode'=>$ExchangeCode,
            'PosId'=>$PosId,
            'SN'=>$SN
        );
        return $this->soapClient_->WontonExchangeInfoQuery($params);
    }

    public function ExchangeSubmit($phoneNo,$params,$Type='2'){
        $params=array(
          'ExchangeCodeList'=>$params['ExchangeCodeList'],
            'PosId'=>$params['PosId'],
            'PosName'=>$params['PosName'],
            'UserId'=>$params['UserId'],
            'UserName'=>$params['UserName'],
            'SaleListNo'=>$params['SaleListNo'],
            'Type'=>$Type,
            'SN'=>$params['SN'],
        );
        return $this->soapClient_->WontonExchangeSubmit($params);
    }

}