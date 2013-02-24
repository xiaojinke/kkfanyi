<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();
class wechatCallbackapiTest
{
  public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $Location_X = $postObj->Location_X;
                $Location_Y = $postObj->Location_Y;
                $location = $Location_X.",".$Location_Y;
                $Label = $postObj->Label;
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";            
   
     
           //自定义回复
                       if(trim($keyword) == "Hello2BizUser")
                {
              		$msgType = "text";
                	$contentStr = trim("可可翻译，微信上最好用的翻译工具，支持中英文互译，快速、准确。提交地理位置信息，还可以获取周边英语培训机构的联系方式哦！");
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }
   
           //获得翻译信息
                       if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = language($keyword);
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
   
                }
           //获得地图信息				
		          if(!empty( $Label ))
                {
              		$msgType = "text";
                	$contentStr = "您好，小可在".$Label."\n"."附近为您找到的英语培训机构："."\n\n".maps($location);
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }	
			
        
        
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}



?>

<?php

function language($value){
 $keyfrom = ""; //申请APIKEY时，填表的网站名称的内容  ；注意： $keyFrom 需要是【连续的英文、数字的组合】
 $apikey = "";  //从有道申请的APIKEY
 $qurl = 'http://fanyi.youdao.com/fanyiapi.do?keyfrom='.$keyfrom.'&key='.$apikey.'&type=data&doctype=json&version=1.1&q='.$value;
 $content = file_get_contents($qurl);
 $sina = json_decode($content,true);
 $errorcode = $sina['errorCode'];
 $trans = $sina['translation']['0'];//输出基本释义
 $phonetic=$sina['basic']['phonetic'];//输出音标
 $result = '';
 
                           
	if(isset($errorcode)){
		switch ($errorcode){
			case 0:
                       
			for($i =0;$i<count($sina['basic']['explains']);$i++){//循环需要的记录
			
				$results = "\n".$sina['basic']['explains'][$i];
		           }
		                $result = $trans."\n".$phonetic.$results;
                                
				break;
			case 20:
				$result = '要翻译的文本过长';
				break;
			case 30:
				$result = '无法进行有效的翻译';
				break;
			case 40:
				$result = '不支持的语言类型';
				break;
			case 50:
				$result = '无效的key';
				break;
			default:
				$result = '出现异常';
				break;
		}
	}
	return $result;
}

?>

<?php
function maps($location){
 $apikey = "";  //从百度地图申请的APIKEY
 $qurl = ' http://api.map.baidu.com/place/search?&query=英语&location='.$location.'&radius=2000&output=json&key='.$apikey;
 $content = file_get_contents($qurl);		
 $arr = json_decode($content,true);//现在已经将结果转成多维数组了
		//print_r($arr);
			
 $result ='';//定义一个空变量，用来盛放最后返回的结果
 $max=0;
		
		if(count($arr['results'])==0){
			$result = "没有找到。。。。。。";
		}else{//否则就全循环出来
			$max = count($arr['results']);
                        for($i =0;$i<$max;$i++){//循环需要的记录
			if(isset($arr['results'][$i]['telephone'])){//不是每条记录都有电话，所以做个判断吧。
				//每循环一次，就在$result后面累加一条记录
				$result = $result.$arr['results'][$i]['name']."\n"."地址：".$address=$arr['results'][$i]['address']."\n"."电话：".$address=$arr['results'][$i]['telephone']."\n\n";
			}else{
				$result = $result.$arr['results'][$i]['name']."\n"."地址：".$address=$arr['results'][$i]['address']."\n\n";
			}
		}
		
		}
		
		
		
               //最后返回$result
return $result;
}
		
?>

