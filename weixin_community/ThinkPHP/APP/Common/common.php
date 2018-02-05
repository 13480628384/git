<?php
/**
 *  快捷获取变量
 **/
class FC {

    public static function P($sKey = null, $sDefault = null) {
        return self::ArrGPBase($sKey, $_POST, $sDefault);
    }

    public static function G($sKey = null, $sDefault = null) {
        return self::ArrGPBase($sKey, $_GET, $sDefault);
    }
    public static function GP($sKey = null, $sDefault = null) {
        return self::ArrGPBase($sKey, array_merge($_GET, $_POST), $sDefault);
    }
    private static function ArrGPBase($sKey = null, $aData = array(), $sDefault = null) {
        if (null === $sKey) return $aData;
        return Arr::get($aData, $sKey, $sDefault);
    }
}

/*
 * 导出为excel
 * $data 数据
 * $title 头部
 * $filename 文件名
 * 如果出现失败网络错误就注释清空缓存ob_end_clean();
 * 用法: exportExcel($data,array('微信昵称','姓名'),'会员资料库');
 * 注意:$data是个二维数组,第二个参数如果数据只有2条，里面的内容对应2条
 * 技术支持：李铭
*/


function exportExcel($data=array(),$title=array(),$filename='report')
{
	$data = array_values($data);
	$title = array_values($title);
	vendor("PHPExcel.PHPExcel");
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	// Set properties
	$objPHPExcel->getProperties()->setCreator("ctos")
	->setLastModifiedBy("ctos")
	->setTitle("Office 2007 XLSX Test Document")
	->setSubject("Office 2007 XLSX Test Document")
	->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
	->setKeywords("office 2007 openxml php")
	->setCategory("Test result file");
	$aSize = range('A', chr(65+count($title)-1));

	foreach ($aSize as $size) {
		//$objPHPExcel->getActiveSheet()->getColumnDimension($size)->setWidth(30);
		$objPHPExcel->getActiveSheet()->getStyle($size.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	}

	//设置行高度
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);

	$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	//set font size bold
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);


	$objSheet = $objPHPExcel->setActiveSheetIndex(0);
	for ($i = 0; $i < count($aSize); $i++) {
		$objSheet->setCellValue($aSize[$i].'1', $title[$i]);
	}

	// Miscellaneous glyphs, UTF-8
	for($i=0;$i<count($data);$i++){
		$j = 0;
		foreach ($data[$i] as $key => $item) {
			$objPHPExcel->getActiveSheet(0)
			->setCellValue($aSize[$j].($i+2), $item);
			$j++;
		}
		$objPHPExcel->getActiveSheet()->getRowDimension($i+2)->setRowHeight(15);
	}
	/*
	 $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+2), 1);
	$objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+2), 'name');
	*/
	// Rename sheet
	//$objPHPExcel->getActiveSheet()->setTitle($filename);


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	//print_r($objPHPExcel);exit;
	//ob_end_clean();
	// Redirect output to a client’s web browser (Excel5)
	header("Pragma: public");

	header("Expires: 0");

	header("Cache-Control:must-revalidate,post-check=0,pre-check=0");

	header("Content-Type:application/force-download");

	header("Content-Type:application/vnd.ms-execl");

	header("Content-Type:application/octet-stream");

	header("Content-Type:application/download");

	header('Content-Disposition:attachment;filename="'.$filename.'.xls"');

	header("Content-Transfer-Encoding:binary");


	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	$objWriter->save('php://output');



    //$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getFont()->setBold(true);

    /*
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    */
    //设置水平居中
    //$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //
    //$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');

    /*
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '会员编号')
            ->setCellValue('B1', '会员名称');
    */
    $objSheet = $objPHPExcel->setActiveSheetIndex(0);
    for ($i = 0; $i < count($aSize); $i++) {
        $objSheet->setCellValue($aSize[$i].'1', $title[$i]);
    }

    // Miscellaneous glyphs, UTF-8
    for($i=0;$i<count($data);$i++){
        $j = 0;
        foreach ($data[$i] as $key => $item) {
            $objPHPExcel->getActiveSheet(0)
                ->setCellValue($aSize[$j].($i+2), $item);
            $j++;
        }
        $objPHPExcel->getActiveSheet()->getRowDimension($i+2)->setRowHeight(15);
    }
    /*
    $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+2), 1);
    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+2), 'name');
    */
    // Rename sheet
    //$objPHPExcel->getActiveSheet()->setTitle($filename);


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    //print_r($objPHPExcel);exit;
    //ob_end_clean();
    // Redirect output to a client’s web browser (Excel5)
    header("Pragma: public");

    header("Expires: 0");

    header("Cache-Control:must-revalidate,post-check=0,pre-check=0");

    header("Content-Type:application/force-download");

    header("Content-Type:application/vnd.ms-execl");

    header("Content-Type:application/octet-stream");

    header("Content-Type:application/download");

    header('Content-Disposition:attachment;filename="'.$filename.'.xls"');

    header("Content-Transfer-Encoding:binary");


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

    $objWriter->save('php://output');
    exit;
}


function p($a=''){
	header("Content-type:text/html;charset=utf-8");
	echo "<pre>";
	print_r($a);
}

function api_notice_increment($url, $data){
    $ch = curl_init();
    $header = "Accept-Charset: utf-8";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);


    if (curl_errno($ch)) {
        return false;
    }else{
        return $tmpInfo;
    }
}




/**
 * 发送邮件
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $to 收件人
 * @param string $attachment_dir 附件地址
 * @return array $res
 * @return array 技术支持：张湘南
 */
function send_email($subject,$body,$to,$attachment_dir=''){
	import('Class.PHPMailer.class#phpmailer',APP_PATH,'.php');
	error_reporting(0);
	$mail = new PHPMailer(); //new一个PHPMailer对象出来
	$mail->CharSet ="utf-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	$mail->IsSMTP(); // 设定使用SMTP服务
	$mail->SMTPDebug  = 1;// 启用SMTP调试功能1 = errors and messages 2 = messages only
	$mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
	$mail->SMTPSecure = "smtp";                 // 安全协议
	$mail->Host       = "smtp.163.com";      // SMTP 服务器
	$mail->Port       = 25;                   // SMTP服务器的端口号
	$mail->Username   = "zhangyinfei313com";  // SMTP服务器用户名
	$mail->Password   = "iloveyouzyf123";            // SMTP服务器密码
	$mail->SetFrom('zhangyinfei313com@163.com', '万普');//发件人
    /*
	$mail->Username   = "zhangyinfei313com";  // SMTP服务器用户名
	$mail->Password   = "iloveyouzyf123";            // SMTP服务器密码
	$mail->SetFrom('zhangyinfei313com@163.com', '万普');//发件人
    */
	$mail->AddReplyTo('lwchun1983@163.com',"多迪E1405");//增加回复标签，参数1地址，参数2名称
	$mail->Subject    = $subject;//邮件主题
	$mail->MsgHTML($body);//
	$mail->AddAddress($to, " ");//增加收件人 参数1为收件人邮箱，参数2为收件人称呼
	if(!empty($attachment_dir)){
		$mail->AddAttachment($attachment_dir);//附件的路径和附件名*/
	}
	$res=array('success'=>false,'info'=>'');
	if(!$mail->Send()) {
		$res['info']=$mail->ErrorInfo;
	} else {
		$res['success']=true;
	}
	return $res;
}







/**  判断真假身份证
 * @param $name  姓名
 * @param $card     身份证号码
 * @param $user     帐号
 * @param $pwd      密码
 * @return string     真假
 */
function is_card($name,$card,$user,$pwd)
{

	return file_get_contents(sprintf('http://www.34team.com/idcard.php?idcard=%s&name=%s', $card, $name));
	return true;
    header("content-type:text/html; charset=utf8");
// 将下面的接口地址修改为签订合同后提供的正式接口地址
// 注意，接口地址中的“IdentifierService.svc”部分, 字母I 和S 必须为大写
    $client = new SoapClient ("http://service.sfxxrz.com/IdentifierService.svc?wsdl");
    $r = new stdClass();
    $r->IDNumber = $card; //要查询的身份证号码
    $r->Name = $name; //要查询的姓名
    $c = new stdClass();
    $c->UserName = $user; // 将“user”修改为签订合同后提供的正式账号
    $c->Password = $pwd; // 将“pwd”修改为签订合同后提供的密码
    $result_json = $client->SimpleCheckByJson(
        array('request' => json_encode($r),
            'cred' => json_encode($c))
    )->SimpleCheckByJsonResult;
    $result = json_decode($result_json);
    if ($result->ResponseText == "成功") {
        if ($result->Identifier->Result == "一致"){
            return true;
        }
        return false;
    }else {
        return false;
    }
}





/**
 * 根据经伟度获取周围店铺
 * @param string $r 距离
 * @param string $data 查询出所有的店铺
 * @param string $long1 当前经度Y
 * @param string $lat1 当前纬度x
 * @return array $res
 * @return string 技术支持：张湘南
 */
function getInfo($r,$data=array(),$long1 ,$lat1){
	$newdata = array();
	foreach($data as $k => $v){
		$lat2=$v['position_x'];//纬度
		$long2=$v['position_y'];//经度

		$distance = getDis($long1,$lat1,$long2,$lat2);
		if($r >= $distance){
			$newdata[$v['id']] = $v;
			$newdata[$v['id']]['distance']=$distance;
		}else{
			unset($data[$k]);
		}
	}
	ksort($newdata,SORT_NUMERIC);
	return $newdata;
}
/**
 * 根据经伟度获取周围店铺中所用到的函数
 * @return string 技术支持：张湘南
 */

function getDis($long1,$lat1,$long2,$lat2){
	$R = 6370996.81;
	return $R*acos(cos($lat1*pi()/180 )*cos($lat2*pi()/180)*cos($long1*pi()/180 -$long2*pi()/180)+ sin($lat1*pi()/180 )*sin($lat2*pi()/180));
}
/*
 * 发送短信
 */
function sendPhomeCode($token, $phone,$info=''){
    return file_get_contents(
        sprintf('http://v.wapwei.com/index.php?g=Wap&m=Sms&a=send_sms&token=%s&type=1&phone=%s&info=%s',
        $token,
        $phone,
        $info
    ));

}

/*
*	兑奖发短信
*/
function senddj($phone,$msg=''){
    return file_get_contents(
        sprintf('http://www.34team.com/message.php?phone=%s&msg=%s',
        $phone,
        $msg
    ));

}

/*
 * 验证发送短信
 */
function validCode($token, $phone, $code){
    /*
    return httpMethod('http://v.wapwei.com/index.php?g=Wap&m=Sms&a=sms_valid', array(
        'token' => $token,
        'type'  => 1,
        'phone' => $phone,
        'code'  => $code
    ));
    */
    return file_get_contents(
        sprintf('http://v.wapwei.com/index.php?g=Wap&m=Sms&a=sms_valid&token=%s&type=1&phone=%s&code=%s',
        $token,
        $phone,
        $code
    ));
}


/**
 *求两个已知经纬度之间的距离,单位为米
 *@param lng1,lng2 经度Y
 *@param lat1,lat2 纬度x
 *@return float 距离，单位米
 *@author www.Alixixi.com
 *@author 技术支持：张湘南
 **/

function getdistance($lng1,$lat1,$lng2,$lat2){
	//将角度转为狐度
	$radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
	$radLat2=deg2rad($lat2);
	$radLng1=deg2rad($lng1);
	$radLng2=deg2rad($lng2);
	$a=$radLat1-$radLat2;
	$b=$radLng1-$radLng2;
	$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
	return $s;
}
/*微信发送消息*/
function msg($token,$openid,$content){
    return httpMethod('http://v.wapwei.com/index.php?g=Home&m=Auth&a=sendTextMsg', array(
        'token'=> $token,
        'openid'=>$openid,
        'content'=> $content
    ));
}

/*微信发送消息*/
function news($token,$openid,$content){
    return httpMethod('http://v.wapwei.com/index.php?g=Home&m=Auth&a=sendNewsMsg', array(
        'token'=> $token,
        'openid'=>$openid,
        'content'=> json_encode($content)
    ));
}

function encode($var) {
    switch (gettype($var)) {
        case 'boolean':
            return $var ? 'true' : 'false'; // Lowercase necessary!
        case 'integer':
        case 'double':
            return sprintf( '"%s"', $var);
        case 'resource':
        case 'string':
            return '"'. str_replace(array("\r", "\n", "\t", '\\\'', "/"),
                array('\r', '\n', '\t', '\'', '\\/'),
                addslashes($var)) .'"';
        case 'array':
            // Arrays in JSON can't be associative. If the array is empty or if it
            // has sequential whole number keys starting with 0, it's not associative
            // so we can go ahead and convert it as an array.
            if ( empty ($var) || array_keys($var) === range(0, sizeof($var) - 1)) {
                $output = array();
                foreach ($var as $v) {
                    $output[] = encode($v);
                }
                return '['. implode(',', $output) .']';
            }
        // Otherwise, fall through to convert the array as an object.
        case 'object':
            $output = array();
            foreach ($var as $k => $v) {
                $output[] =  encode(strval($k)) .':'.  encode($v);
            }
            return '{'. implode(',', $output) .'}';
        default:
            return 'null';
    }
}



function isMyUsers($token,$openid){
    $Wxuser=M('Wxuser')->field('id')->where(array('token'=>$token))->find();
    if($Wxuser){
        $Wxusers=M('Wxusers')->field('id')->where(array('uid'=>$Wxuser['id'],'openid'=>$openid,'status'=>1))->find();
        if($Wxusers){
            return 1;
        }else{
            return 2;
        }
    }else{
        return 3;
    }
}


/*
 *  发送模板消息
 */
function tmpl($token, $openid, $data)
{
    if(!empty($token) && !empty($openid)){
        $code = isMyUsers($token,$openid);
        if($code == 1){
            $accesstoken = getAccessToken($token);
            if($accesstoken){
                $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accesstoken;
                $api_content = $data;
                $api_content['touser']= $openid;
                $api_content = encode($api_content);
                $returninfo = api_notice_increment($url,$api_content);
                if($returninfo){
                    return array('code'=>0,'msg'=>'发送成功');
                }
            }else{
                return array('code'=>-1,'msg'=>'获取access_token失败');
            }
        }else if($code == 2){
            return array('code'=>-2,'msg'=>'不能给没有关注您的用户发送消息');
        }else{
            return array('code'=>-3,'msg'=>'非法请求');
        }

    }
}

/**
 *验证会员，成功跳回子页面
 *@param $string 会员地址
 *@param $openid
 *@author 技术支持：张湘南
 *用法:MemberYz($string='Mhyzx/zc',$_GET['openid'])
 **/
//会员注册成功处的跳转复制下面代码
/* if($_SESSION['url']){
 echo '<script>alert("注册成功");location.href="'.C('site_url').$_SESSION['url'].'"</script>';die;
}else{
echo '<script>alert("注册成功");location.href="跳原来的地址"</script>';die;
} */
function MemberYz($string='Mhyzx/zc',$openid=''){
	$uid=M('wxuser')->where(array('token'=>$_GET['token']))->getField('id');
	$member=M('Usercenter_memberlist')->where(array('openid'=>$openid,'uid'=>$uid))->find();
	if(!$member){
		$_SESSION['url']=$_SERVER['REQUEST_URI'];//存地址
		echo $_SESSION['url'];
		echo '<script>alert("您还不是会员,请注册会员");location.href="'.U($string,array('token'=>$_GET['token'],'openid'=>$openid)).'"</script>';die;
	}
}
//给出两个日期算中间有多少个月分
function getMonthNum($start,$end){
    $start=strtotime($start);
    $end=strtotime($end);
    $start_y=date('Y',$start);
    $start_m=date('m',$start);
    $start_d=date('d',$start);


    $end_y=date('Y',$end);
    $end_m=date('m',$end);
    $end_d=date('d',$end);
    $m=($end_y-$start_y)*12+$end_m-$start_m;
    if($end_d<=$start_d){
        $m=$m-1;
    }
    return $m;
}


/**
 *js弹出跳转
 *$string 弹出内容
 *$address 跳转地址
 *第二个参数不传默认转回当前地扯
 *用法:script('万普万岁','Wap/Mrugr/index')
 *@author 技术支持：张湘南
 **/
function script($string,$address,$get){
	if($address){
		if($string){
			echo '<script>alert("'.$string.'");location.href="'.U($address,$get).'"</script>';die;
		}else{
			echo '<script>location.href="'.U($address,$get).'"</script>';die;
		}
	}else{
		if($string){
			echo '<script>alert("'.$string.'");history.back();</script>';die;
		}else{
			echo '<script>history.back();</script>';die;
		}
	}
}


/**
 *快速获取url变量
 *@param $string $token,$token2,$token3,$token4...
 *@author 技术支持：张湘南
 *用法:get(token,openid.id);
 **/
function get($token,$token2,$token3,$token4,$token5,$token6,$token7){
	$a=array($token=>$_GET[$token],$token2=>$_GET[$token2],$token3=>$_GET[$token3],$token4=>$_GET[$token4],$token5=>$_GET[$token5],$token6=>$_GET[$token6],$token7=>$_GET[$token7]);
	return $a;
}
/**
 *快速获取url变量,自定义1个变量
 *@param $string $token,$token2,$token3,$token4...
 *@author 技术支持：张湘南
 **/
function get1($token,$token2,$token3,$token4,$token5,$token6,$token7,$token8,$token9,$token10,$token11,$token12,$token13,$token14,$token15,$token16,$token17,$token18,$token19,$token20){
	$a=array($token=>$token2,$token3=>$_GET[$token3],$token4=>$_GET[$token4],$token5=>$_GET[$token5],$token6=>$_GET[$token6],$token7=>$_GET[$token7],$token8=>$_GET[$token8],$token9=>$_GET[$token9],$token10=>$_GET[$token10],$token11=>$_GET[$token11],$token12=>$_GET[$token12],$token13=>$_GET[$token13],$token14=>$_GET[$token14],$token15=>$_GET[$token15],$token16=>$_GET[$token16],$token17=>$_GET[$token17],$token18=>$_GET[$token18],$token19=>$_GET[$token19],$token20=>$_GET[$token20]);
	$a=array_filter($a);
	return $a;
}
function get2($token,$token2,$token3,$token4,$token5,$token6,$token7,$token8,$token9,$token10,$token11,$token12,$token13,$token14,$token15,$token16,$token17,$token18,$token19,$token20){
	$a=array($token=>$token2,$token3=>$token4,$token5=>$_GET[$token5],$token6=>$_GET[$token6],$token7=>$_GET[$token7],$token8=>$_GET[$token8],$token9=>$_GET[$token9],$token10=>$_GET[$token10],$token11=>$_GET[$token11],$token12=>$_GET[$token12],$token13=>$_GET[$token13],$token14=>$_GET[$token14],$token15=>$_GET[$token15],$token16=>$_GET[$token16],$token17=>$_GET[$token17],$token18=>$_GET[$token18],$token19=>$_GET[$token19],$token20=>$_GET[$token20]);
	$a=array_filter($a);
	return $a;
}
function get3($token,$token2,$token3,$token4,$token5,$token6,$token7,$token8,$token9,$token10,$token11,$token12,$token13,$token14,$token15,$token16,$token17,$token18,$token19,$token20){
	$a=array($token=>$token2,$token3=>$token4,$token5=>$token6,$token7=>$_GET[$token7],$token8=>$_GET[$token8],$token9=>$_GET[$token9],$token10=>$_GET[$token10],$token11=>$_GET[$token11],$token12=>$_GET[$token12],$token13=>$_GET[$token13],$token14=>$_GET[$token14],$token15=>$_GET[$token15],$token16=>$_GET[$token16],$token17=>$_GET[$token17],$token18=>$_GET[$token18],$token19=>$_GET[$token19],$token20=>$_GET[$token20]);
	$a=array_filter($a);
	return $a;
}
function get4($token,$token2,$token3,$token4,$token5,$token6,$token7,$token8,$token9,$token10,$token11,$token12,$token13,$token14,$token15,$token16,$token17,$token18,$token19,$token20){
	$a=array($token=>$token2,$token3=>$token4,$token5=>$token6,$token7=>$token8,$token9=>$_GET[$token9],$token10=>$_GET[$token10],$token11=>$_GET[$token11],$token12=>$_GET[$token12],$token13=>$_GET[$token13],$token14=>$_GET[$token14],$token15=>$_GET[$token15],$token16=>$_GET[$token16],$token17=>$_GET[$token17],$token18=>$_GET[$token18],$token19=>$_GET[$token19],$token20=>$_GET[$token20]);
	$a=array_filter($a);
	return $a;
}
/**
 *快速获取post变量
 **/
function post($token,$token2,$token3,$token4,$token5,$token6,$token7){
	$a=array($token=>$_POST[$token],$token2=>$_POST[$token2],$token3=>$_POST[$token3],$token4=>$_POST[$token4],$token5=>$_POST[$token5],$token6=>$_POST[$token6],$token7=>$_POST[$token7]);
	$a=array_filter($a);
	return $a;
}

function MruMember($string='Mhyzx/zc',$openid=''){
	$member=M('mru_jfb')->where(array('openid'=>$openid,'token'=>$_GET['token']))->find();
	if(!$member){
		$_SESSION['url']=$_SERVER['REQUEST_URI'];//存地址
		//echo $_SERVER['REQUEST_URI'];
		echo '<script>alert("您还不是会员,请注册会员");location.href="'.U($string,array('token'=>$_GET['token'],'openid'=>$openid)).'"</script>';die;
	}

}
/**
 *日志操作
 *$title 标题;
 *$content 内容
 *$type 1为用户日志，2为店铺日志
 *用法:rz('我要应聘','提交了应聘信息请在后台加入我们中查看详情');
 *技术支持:张湘南
 **/
function rz($title,$content,$type=1){
	M('rz')->add(array('name'=>$title,'content'=>$content,'openid'=>OP,'token'=>TO,'add_time'=>time(),'type'=>$type));
}

/*
 *  转换偏移
 */
function transgps($lats,$lngs, $gps=false, $google=false)
{
    $lat=$lats;
    $lng=$lngs;
    if($gps)
        $c=file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=$lng&y=$lat");
    else if($google)
        $c=file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=2&to=4&x=$lng&y=$lat");
    else
    return array($lat,$lng);
    $arr=(array)json_decode($c);
    if(!$arr['error'])
    {
        $lat=base64_decode($arr['y']);
        $lng=base64_decode($arr['x']);
    }
    return array($lat,$lng);
}












//获得唯一随机数
    function getSn(){

        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    function downloadWeixinFile($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package));
        return $imageAll;
    }

    function saveWeixinFile($filename, $filecontent){
        file_put_contents($filename, $filecontent);
    }

    function ALog($file, $content){
    	file_put_contents($file, $content, FILE_APPEND);
    }
/**
 *当前用户的微信资料
 *地址栏得带token与openid
 *用法:$wxusers=wxusers();print_r($wxusers);headimgurl头像子段  nickname名称
 *@author 技术支持：张湘南
 **/
    function wxusers(){
    	$uid=M('wxuser')->where(array('token'=>TO))->getField('id');
    	$wxusers=M('wxusers')->where(array('openid'=>OP,'uid'=>$uid))->find();
    	return $wxusers;
    }
/**
 *当前时间往后的时间
 *$int 数字
 *$string 时间格式
 *用法:$date=date_date(30,'Y-m-d');
 *@author 技术支持：张湘南
 **/
    function date_date($int,$string){
    	$a = -86400;//因为for有个$a+=86400; 为了取当前选定时间-86400
    	for ($i=0; $i<$int; $i++){
    		$a+=86400;
    		$aDate[] = date($string,time()+$a);
    	}
    	return $aDate;
    }
/**
 *载取字符串
 *$string 字符串
 *$int 字数
 *$string2 符号
 *用法:str_substr($adviser['exper'],10);
 *@author 技术支持：张湘南
**/
    function str_substr($string,$int,$string2){
    	if(strlen($string) > floor($int)*3){
    		return (mb_substr(strip_tags($string), 0, $int, 'utf-8')).$string2;
    	}else{
    		return (mb_substr(strip_tags($string), 0, $int, 'utf-8'));
    	}
    }
/**
 *删除有序数组中相同的元素
 *$array  二维数组
 *$string 子段
 *@author 技术支持：张湘南
 *用法:array_delete($list,'wg');
**/
    function array_delete($array,$string){
    	foreach ($array as $ke => $v){
    		$array[$v[$string]][$string]=$v[$string];
    		$array[$v[$string]]=$v;
    		unset($array[$ke]);//删除原来的
    	}
    	unset($array['']);//删除键名为空的
    	return $array;
    }



    /**
     *删除有序数组中相同的元素
     *$array  二维数组
     *$string 子段
     *@author 技术支持：张湘南
     *用法:array_delete($list,'wg');
     **/
    function array_delete2($array,$string){
    	foreach ($array as $ke => $v){
    		$array[$v[$string]][$string]=$v[$string];
    		$array[$v[$string]]=$v;
    		unset($array[$ke]);//删除原来的
    	}
    	unset($array['']);//删除键名为空的
    	return $array;
    }
//二维json数据转换成二维数组
function json2arr($json){
    $arr = array();
    foreach((array)$json as $key=>$val){
        if(is_object($val))$arr[$key] = json2arr($val);
        else $arr[$key] = $val;
    }
    return $arr;
}

function msub($str, $len, $default='...')
{
    if (mb_strlen($str) <= $len) {
        return $str;
    }
    return mb_substr($str,0,$len,'utf-8') . $default;
}

function WL($msg, $file='log.log'){
	file_put_contents($file, $msg . "\r\n", FILE_APPEND);
}

function getAccessToken($token){
    $api=M('Diymen_set')->where(array('token'=>$token))->find();
    if($api){
        $url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$api['appid'].'&secret='.$api['appsecret'];
        $json=json_decode(file_get_contents($url_get));
        if(!isset($json->access_token)){
            return false;
        }else{
            return $json->access_token;
        }
    }else{
        return false;
    }
}

function vanke_user($openid){
    return array(
        'head'  => 1,
        'name'  => 2
    );
}

/*
    ***聚合数据（JUHE.CN）数据接口调用通用DEMO SDK
    ***DATE:2014-04-14
*/
function getDataInfo($date=null){
    $appkey='a226ec8955e1d843675d5ca35542abd8'; #通过聚合申请到数据的appkey

    $url ='http://japi.juhe.cn/calendar/day'; #请求的数据接口URL

    $date = $date ? $date : date('Y-m-d');
    $params ='ip=www.juhe.cn&date='.$date.'&key='.$appkey;

    $content = juhecurl($url,$params,0);

    if($content){
        $result =json_decode($content,true);
        #print_r($result);

        #错误码判断
        $error_code = $result['error_code'];
        if($error_code ==0){
            #根据所需读取相应数据
            $data = $result['result'];
            $data = $data['data'];
            return $data;
        }else{
            return false;
        }
    }
}




/*
    ***请求接口，返回JSON数据
    ***@url:接口地址
    ***@params:传递的参数
    ***@ispost:是否以POST提交，默认GET
*/
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_0 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        #echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}
function posts($url, $data){
    $postdata = http_build_query(
        $data
    );       
    $opts = array('http' =>
                  array(
                      'method'  => 'POST',
                      'header'  => 'Content-type: application/x-www-form-urlencoded;charset=UTF-8',
                      'content' => $postdata
                  )
    );        
    $context = stream_context_create($opts); 
    $result = file_get_contents($url, false, $context); 
    return $result;  
}

